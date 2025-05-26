<?php

use App\Constants\StatusConstants;
use App\Models\AppSetting;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetLocation;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Customer;
use App\Models\DeliveryArea;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseItem;
use App\Models\Guest;
use App\Models\GymMember;
use App\Models\restaurant;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemSubCategory;
use App\Models\MenuCategory;
use App\Models\Outlet;
use App\Models\Purchase;
use App\Models\PurchaseCategory;
use App\Models\PurchaseItem;
use App\Models\Role;
use App\Models\Room;
use App\Models\RoomCategory;
use App\Models\RoomReservation;
use App\Models\StoreItem;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Magarrent\LaravelCurrencyFormatter\Facades\Currency;
use Sqids\Sqids;

if (!function_exists('title')) {
    function title($title = null)
    {
        if (!empty($title)) {
            return $title . " - " . config('flowdash.brand');
        }

        return config('flowdash.brand');
    }
}

if (!function_exists('activeClass')) {
    function activeClass($route, $activeClass = 'active')
    {
        return request()->routeIs($route) ? $activeClass : '';
    }
}

if (!function_exists('theme_view')) {
    function theme_view($view, $data = [], $mergeData = [])
    {
        $theme = config('theme.active');
        $path = $theme . '.' . $view;

        if (view()->exists($path)) {
            return view($path, $data, $mergeData);
        }

        // fallback to default theme or flat view
        return view($view, $data, $mergeData);
    }
}

if (!function_exists('arrayToObject')) {
    function arrayToObject($d)
    {
        if (is_array($d)) {
            /*
      * Return array converted to object
      * Using __FUNCTION__ (Magic constant)
      * for recursive call
      */
            return (object) array_map(__FUNCTION__, $d);
        } else {
            // Return object
            return $d;
        }
    }
}

function getSettings()
{
    return restaurant()->appSetting;
}

if (!function_exists('activeModule')) {
    function activeModule($module)
    {
        return getSettings()->{$module};
    }
}

function formatCurrency($amount)
{
    return "₦" . number_format($amount, 2);
}
function getModelList($model)
{
    $user = auth()->user();
    $restaurant = $user->restaurant;
    $restaurant_id = $user->restaurant_id;
    $outlet_id = outlet()->id;

    return match ($model) {
        'countries' => DB::select('select id, name from countries'),
        'states' => DB::table('states')->where('country_id', 161)->orderBy('name')->get(),
        'expense-categories' => ExpenseCategory::whereIn('restaurant_id', [0, $restaurant_id])->orderBy('name')->get(),
        'suppliers' => Supplier::where('restaurant_id', $restaurant_id)->get(),
        'purchases' => Purchase::where('store_id', $restaurant->store->id)->get(),
        'expense-items' => ExpenseItem::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'store-items' => StoreItem::where('store_id', $restaurant->store->id)->orderBy('name')->get(),
        'room-categories' => RoomCategory::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'item-categories' => ItemCategory::all(),
        'item-sub_categories' => ItemSubCategory::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'rooms' => Room::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'venues' => Venue::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'customers' => Customer::where('restaurant_id', $restaurant_id)->orderBy('first_name')->get(),
        'gym-members' => GymMember::where('restaurant_id', $restaurant_id)->get(),
        'taxes' => Tax::where('restaurant_id', $restaurant_id)->where('Active', true)->get(),
        'outlets' => Outlet::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'bar-outlets' => Outlet::where('restaurant_id', $restaurant_id)->where('type', 'bar')->get(),
        'restaurant-outlets' => Outlet::where('restaurant_id', $restaurant_id)->get(),
        'outlets' => Outlet::where('restaurant_id', $restaurant_id)->get(),
        'menu-categories' => MenuCategory::where('outlet_id', $outlet_id)->get(),
        'kitchen-outlets' => Outlet::where('restaurant_id', $restaurant_id)->where('type', 'kitchen')->get(),
        'kitchen-store-items' => StoreItem::where('store_id', $restaurant->store->id)
            ->where('item_category_id', 1)
            ->where('for_sale', 1)
            ->orderBy('name')
            ->get(),
        'housekeepers' => User::where('restaurant_id', $restaurant_id)->where('role', 'Housekeeper')->get(),
        'roles' => Role::all(),
        'restaurant-types' => ['restaurant' => 'Restaurant', 'fast-food' => 'Fast Food'],
        'parent-expense-categories' => ExpenseCategory::where('restaurant_id', 0)->orderBy('name')->get(),
        'bank-accounts' => BankAccount::where('restaurant_id', $restaurant_id)->orderBy('account_name')->get(),
        'companies' => Company::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'asset-location-types' => ['Room' => 'room', 'store' => 'Store', 'bar' => 'Bar', 'restaurant' => 'Restaurant'],
        'asset-working-conditions' => ['Working' => 'working', 'Not Working' => 'not_working'],
        'asset-locations' => AssetLocation::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'assets' => Asset::where('restaurant_id', $restaurant_id)->orderBy('name')->get(),
        'asset-categories' => AssetCategory::whereIn('restaurant_id', [1, $restaurant_id])->orderBy('name')->get(),
        'genders' => ['Female' => 'female', 'Male' => 'male'],
        'delivery-areas' => DeliveryArea::where('state_id', $restaurant->state_id)->orderBy('name')->get(),

        default => null,
    };
}


function isValidYMDFormat($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function getDateDifferenceInFineFormat($startDate, $endDate)
{
    // Convert to Carbon instances
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate);

    // Calculate the difference in a human-readable format
    $diffString = $start->diffForHumans($end, CarbonInterface::DIFF_ABSOLUTE, false, 2); // e.g. "1 month"
    $label = "Last $diffString";

    return $label;
}

function calculateTaxRate()
{
    return 0;
}
function calculateTaxAmount($amount)
{
    //get all active tax and then apply to the amount 
    // Fetch all active taxes from the database
    $activeTaxes = Tax::where('restaurant_id', restaurantId())->where('is_active', true)->get();

    $totalTaxAmount = 0;

    // Apply each tax to the amount
    foreach ($activeTaxes as $tax) {
        // Calculate tax amount for the current tax
        $taxAmount = ($tax->rate / 100) * $amount;

        // Add to the total tax amount
        $totalTaxAmount += $taxAmount;
    }

    return $totalTaxAmount;
}
function getAppliedTaxes($amount)
{
    $activeTaxes = Tax::where('restaurant_id', restaurantId())->where('is_active', true)->get();

    $taxes = [];

    // Apply each tax to the amount
    foreach ($activeTaxes as $tax) {
        // Calculate tax amount for the current tax
        $taxAmount = ($tax->rate / 100) * $amount;

        $taxes[$tax->name] = $taxAmount;
    }

    return $taxes;
}

function calculateTotalAmountWithTax($amount)
{
    return calculateTaxAmount($amount) + $amount;
}
function calculateTotalBillAmount($amount)
{
    //this will return the total amount for any bill depending on the type of tax applied
    //this function will be called on every bill
    $calculatedAmount = $amount;
    if (restaurant()->appSetting->include_tax == false) {
        $calculatedAmount = calculateTotalAmountWithTax($amount);
    }

    return $calculatedAmount;
}
function calculateAmount($qty, $price)
{
    return $qty * $price;
}

function convertDurationToDays($duration)
{
    switch (strtolower($duration)) {
        case 'daily':
            return 1; // 1 day
        case 'weekly':
            return 7; // 7 days
        case 'monthly':
            return 30; // Approximate average of days in a month
        case 'quarterly':
            return 91; // Approximate average of days in a quarter (3 months)
        case 'bi-annually':
            return 182; // Approximate average of days in 6 months
        case 'yearly':
            return 365; // Number of days in a year
        default:
            return 0; // Unknown duration
    }
}

function getBanksList()
{
    $banks = [
        'Access Bank Plc',
        'Fidelity Bank Plc',
        'First City Monument Bank Plc',
        'First Bank of Nigeria Limited',
        'Guaranty Trust Bank Plc',
        'Union Bank of Nigeria Plc',
        'United Bank for Africa Plc',
        'Zenith Bank Plc',
        'Citibank Nigeria Limited',
        'Ecobank Nigeria Plc',
        'Heritage Banking Company Limited',
        'Keystone Bank Limited',
        'Polaris Bank Limited. (Formerly Skye Bank Plc)',
        'Stanbic IBTC Bank Plc',
        'Standard Chartered',
        'Sterling Bank Plc',
        'Titan Trust Bank Limited',
        'Unity Bank Plc',
        'Wema Bank Plc',
        'Globus Bank Limited',
        'SunTrust Bank Nigeria Limited',
        'Providus Bank Limited',
        'Jaiz Bank Plc',
        'Taj Bank Limited',
        'Coronation Merchant Bank',
        'FBNQuest Merchant Bank',
        'FSDH Merchant Bank',
        'Rand Merchant Bank',
        'Nova Merchant Bank'
    ];
    return $banks;
}

function restaurantId()
{
    return auth()->user()->restaurant->id;
}

function restaurant()
{
    //if the account has multiple restaurants, get the restaurant that the user is currently tied to
    //else return the default restaurant
    if (auth()->user()->userAccount->restaurants->count() > 1) {
        return auth()->user()->restaurant;
    }
    return auth()->user()->userAccount->restaurant;
}

function outlet()
{
    return auth()->user()->outlet;
}

function restaurants()
{
    return auth()->user()->userAccount->restaurants;
}

function removeUnderscore($string)
{
    return str_replace('_', ' ', $string);
}

function removeUnderscoreAndCapitalise($string)
{
    return ucwords(str_replace('_', ' ', $string));
}

function Capitalise($string)
{
    return ucwords($string);
}

function removeDashAndConvertToPascalCase($string)
{
    return ucwords(str_replace('-', ' ', $string));
}

function getItemCategoryId($category)
{
    $category_id = 0;
    switch ($category) {
        case "Food":
            $category_id = 1;
            break;
        case "Drink":
            $category_id = 2;
            break;
        case "Housekeeping":
            $category_id = 3;
            break;
        case "Maintenance":
            $category_id = 4;
            break;
        case "Staff":
            $category_id = 5;
            break;
        case "Administrative":
            $category_id = 6;
            break;
        case "Others":
            $category_id = 7;
            break;

        default:
            # code...
            break;
    }
    return $category_id;
}

function generateUniqueItemCode()
{
    $maxAttempts = 1000; // Set a limit to the number of attempts to avoid infinite loops
    $attempts = 0;

    do {
        $itemCode = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $exists = StoreItem::where('code', $itemCode)->exists();

        $attempts++;

        if ($attempts > $maxAttempts) {
            throw new \Exception("Unable to generate unique item code after $maxAttempts attempts.");
        }
    } while ($exists); // Check if the code already exists

    return $itemCode;
}

function formatNumber($number)
{
    if ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    } else {
        return number_format($number);
    }
}

function formatDate($dateAttribute)
{
    $carbonDate = Carbon::createFromFormat('Y-m-d', $dateAttribute);
    return $carbonDate->format('d-M-Y');
}

function formatTime($timeAttribute)
{
    $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $timeAttribute);
    return $carbonDate->format('d-M h:i a');
}

function formatDateSpecial($date)
{
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('jS, M Y');
}

function userRestaurantOutlet()
{
    $user = auth()->user();
    $defaultRestaurant = $user->restaurant->defaultRestaurant();

    // Use null coalescing operator to simplify condition
    $restaurant_outlet = $user->outlet ?? $defaultRestaurant;

    // Ensure the outlet is of type 'restaurant', otherwise fallback to default restaurant
    return $restaurant_outlet && $restaurant_outlet->type == 'restaurant'
        ? $restaurant_outlet
        : $defaultRestaurant;
}

function eraseDuplicateCreditPayment($order)
{
    $payments = $order->payments;
    $isCreditOrder = false;
    // Check if there is a credit payment
    $creditPayments = $payments->filter(function ($payment) {
        return $payment->payment_method == 'credit';
    });

    if ($creditPayments->isNotEmpty()) {
        // Calculate the sum of non-credit payments
        $nonCreditSum = $payments->filter(function ($payment) {
            return $payment->payment_method != 'credit';
        })->sum('amount');

        // If the sum of non-credit payments equals or exceeds the order amount
        //if the order has total_amount use it else use amount
        $amount = $order->amount;
        if ($order->total_amount) {
            $amount = $order->total_amount;
        }
        if ($nonCreditSum >= $amount) {
            // Delete all credit payments
            foreach ($creditPayments as $creditPayment) {
                $creditPayment->delete();
            }

            $isCreditOrder = true;
        }
    }
    return $isCreditOrder;
}

function getPayerWallet($payer, $invoice)
{
    if ($invoice->company_id) {
        $payer = Company::find($invoice->company_id);
    }

    $wallet = get_class($payer) == Company::class ? $payer->companyWallet : $payer->guestWallet;
    return $wallet;
}

function eraseCreditPayment($order)
{
    $payments = $order->payments;
    // Check if there is a credit payment
    $creditPayments = $payments->filter(function ($payment) {
        return $payment->payment_method == 'credit';
    });

    if ($creditPayments->isNotEmpty()) {
        // Delete all credit payments
        foreach ($creditPayments as $creditPayment) {
            $creditPayment->delete();
        }
    }
}

function sendEbulkSms($recipients, $message)
{
    $api_key = restaurant()->appSetting->sms_api_key;
    $username = restaurant()->appSetting->sms_api_username;
    $sender = restaurant()->appSetting->sms_api_sender;
    $request_url = 'https://api.ebulksms.com:443/sendsms?username=' . $username . '&apikey=' . $api_key . '&sender=' . $sender . '&messagetext=' . $message . '&flash=0&recipients=' . $recipients;
    $sms_response = "";
    try {
        if (env('APP_DEBUG') == false) {
            $sms_response = Http::get($request_url);
        }
    } catch (\Exception $e) {
        logger($e->getMessage());
    }

    return $sms_response;
}
