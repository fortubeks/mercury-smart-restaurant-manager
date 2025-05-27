function formatCurrency(number){
    const formattedNumber = new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN'
      }).format(number);
      return formattedNumber;
}