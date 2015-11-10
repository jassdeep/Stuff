var total = 0;

function $(name){
	return document.getElementById(name);
}

function isEmpty(string){
	return string.trim () == "";
}

function isChecked(inputSet){
	if ((inputSet).type == "select-one") {
		if (inputSet.options[inputSet.selectedIndex].value != "0"){
			return true;
		}
	} else if (inputSet.type == "checkbox"){
		if(inputSet.checked){
			return true;
		}
	} else if (inputSet.type == "radio"){
		if(inputSet.checked){
			return true;
		}
	}
	
	return false;
}

function isEmailValid(email){
	reEmail = /\w+@\w+\.\w+/;
	if(reEmail.test(email)){
		return true;
	}
	
	return false;
}

function isNumber(number){
	if (isNaN(number)){
		return false;
	}
	return true;
}

function isPhoneValid(phone){
	if(phone.length == 10){
        for (var i = 0; i < phone.length; i++){
            if (!isNumber(phone.charAt(i))){
                return false;
            }
        }
    }
    	
	return true;
}

function isPostalCodeValid(postal){
	rePostal = /[A-Z]{1}\d{1}[A-Z]{1}\s\d{1}[A-Z]{1}\d{1}/;
	if(rePostal.test(postal.toUpperCase())){
		return true;
	}
	
	return false;
}

function validatePersonal(){
	
	if (isEmpty($('inputName').value)){
		$('personName').className += " has-error";
		return false;
	}
	if (isEmpty($('inputPhone').value) || !isPhoneValid($('inputPhone').value)){
		$('phone').className += " has-error";
		$('inputPhone').value = "";
		return false;
	}
	if (isEmpty($('inputEmail').value) || !isEmailValid($('inputEmail').value)){
		$('email').className += " has-error";
		return false;
	}
	if (isEmpty($('inputStreet').value)){
		$('street').className += " has-error";
		return false;
	}
	if (isEmpty($('inputCity').value)){
		$('city').className += " has-error";
		return false;
	}	
	if (isEmpty($('inputPostalCode').value) || !isPostalCodeValid($('inputPostalCode').value)){
		$('postal').className += " has-error";
		return false;
	}
	if(!isChecked($('selectProvince'))){
		$('province').className += " has-error";
		return false;
	}
	
	return true;
	
}

function validateOrder(){
	var result = true;
	
	sumTotal();
	
	if (!confirm('Your total is CAD$' + Number(total).toFixed(2)  + '. \nCould you confirm please?')){
		result = false;
		total = 0;
	} else{
		result = true;
	}	
	
	return result;
}

function sumTotal(){
    if (isChecked($('small'))){
		total += 5;
	} else if (isChecked($('med'))){
		total += 10;
	} else if (isChecked($('large'))){
		total += 15;
	} else if (isChecked($('xl'))){
		total += 20;
	}
	
	if (isChecked($('stuffed'))){
		total += 2;
	}
	
	if (countToppings() > 0){
		total += countToppings() * 0.5;
	}
	
	calculateTaxes();
	
}

function calculateTaxes(){
	if (isChecked($('selectProvince'))) {
		if ($('selectProvince').options[$('selectProvince').selectedIndex].value == "ON"){
			total = total * 1.13;
		} else if ($('selectProvince').options[$('selectProvince').selectedIndex].value == "QC"){
			total = total * 1.14975;
		} else if ($('selectProvince').options[$('selectProvince').selectedIndex].value == "MB"){
			total = total * 1.08;
		} else if ($('selectProvince').options[$('selectProvince').selectedIndex].value == "SK"){
			total = total * 1.10;
		}
	}
}

function countToppings(){
	var count = 0;
	var inputElements = document.getElementsByTagName('input');
	for(var i=0; inputElements[i]; ++i){
      if(inputElements[i].type == 'checkbox' && isChecked(inputElements[i])){
		  count++;
	  }
	}
	
	if (count > 1){
		count--;
	}
	
	return count;
}

function validateAll(){
	return validatePersonal() && validateOrder();
}