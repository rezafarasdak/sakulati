// JavaScript Document

function sameDataCustomerTax(){
	
	var checkedStatus = document.formCustomer.customerSameData.checked;
	
	if (checkedStatus == true){
		
		document.formCustomer.nama_persh.value = document.formCustomer.tax_cust_name.value;
		document.formCustomer.alamat.value = document.formCustomer.tax_address.value;

	}else{

		document.formCustomer.nama_persh.value = document.formCustomer.hide_nama_persh.value;
		document.formCustomer.alamat.value = document.formCustomer.hide_alamat.value;
		
	}
}


function sameDataSuplierTax(){
	
	var checkedStatus = document.formSuplier.suplierSameData.checked;
	
	if (checkedStatus == true){
		
		document.formSuplier.nama_persh.value = document.formSuplier.tax_name.value;
		document.formSuplier.alamat.value = document.formSuplier.tax_address.value;

	}else{

		document.formSuplier.nama_persh.value = document.formSuplier.hide_tax_name.value;
		document.formSuplier.alamat.value = document.formSuplier.hide_tax_address.value;
		
	}
}


function validateForm()
{
	
	var r=confirm("Pastikan data yang di input sudah benar, apa anda yakin?","Ya");
	if (r==true){
		return true;
	}else{
    	return false;
	    alert("Anda Menekan Tombol CANCEL!");
	}
	
	return false;
}

function validateCheckBox()
{
	var total = 0;
	var jum = document.form1.jumlah_barang.value;

	for (var idx = 1; idx <= jum; idx++) {
		box = eval("document.form1.id_barang_" + idx);
		if(box.checked == true){
			total += 1;		
		}
	}

	if(total == 0){
		alert("Anda Belum Memilih Barang");
		return false;	
	}else{
		return true;
	}

}

function validateCheckBoxInvoice()
{
	var total = 0;
	var jum = document.form1.jumlah_do.value;

	for (var idx = 1; idx <= jum; idx++) {
		box = eval("document.form1.id_do_" + idx);
		if(box.checked == true){
			total += 1;		
		}
	}

	if(total == 0){
		alert("Anda Belum Memilih Surat Jalan");
		return false;	
	}else{
		return true;
	}

}

function validateCheckBoxInvoicePO()
{
	var total = 0;
	var jum = document.form1.jumlah_do.value;

	for (var idx = 1; idx <= jum; idx++) {
		box = eval("document.form1.id_do_" + idx);
		if(box.checked == true){
			total += 1;		
		}
	}

	if(total == 0){
		alert("Anda Belum Memilih Good Receipt");
		return false;	
	}else{
		return true;
	}

}

function validateCheckBoxPelunasan()
{
	var total = 0;
	var jum = document.form1.jumlahInvoice.value;
	var currency = "";
	var oldCurrency = "";

	for (var idx = 1; idx <= jum; idx++) {
		box = eval("document.form1.inv_" + idx);
		if(box.checked == true){
			total += 1;		
			
			// Start Check Double Currency
			if(oldCurrency == ""){
				oldCurrency = eval("document.form1.currency_" + idx + ".value");
			}
			
			currency = eval("document.form1.currency_" + idx + ".value");
			if(idx > 1){
				if (currency != oldCurrency){
					alert("Anda Tidak Bisa Memilih Lebih Dari 1 Currency, Mohon Un Check Salah Satu");	
					return false;
				}else{
					oldCurrency = eval("document.form1.currency_" + idx + ".value");					
				}
			}		
			
			// END
		}
		
		
	}

	if(total == 0){
		alert("Anda Belum Memilih Invoice");
		return false;	
	}else{
		return true;
	}

}


function uraian(){

	var y = document.form1.jumBarang.value;

	header = "";
	a1 = "";

	for(j = 4; j<=y; j++){

		a1 +=' 	<tr><td>' + j + '. &nbsp;&nbsp;&nbsp;&nbsp;</td>';
		a1 +='	<td><select name=id_barang_' + j + ' onchange="document.form1.harga_satuan_' + j + '.value = prdName[this.value];calculate();"> <option value="">----- PILIH BARANG -----</option> {menuBarangJs} </select></td>';
		a1 +='	<td>&nbsp;&nbsp;&nbsp;<input type="teks" name="harga_satuan_' + j + '" size="10"  class="inp-form" value="0" onchange="calculate()"></td>';
		a1 +='	<td>&nbsp;&nbsp;&nbsp;<input type="teks" name="quantity_' + j + '" size="5"  class="inp-form" value="1" onchange="calculate()"></td>';
		a1 +='	<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="teks" name="harga_total_' + j + '" size="10"  class="inp-form" id="harga_total_' + j + '"></td>';
		
		a1 +=' </tr>';
	}
	
	document.getElementById("rincianUraian").innerHTML = '<table>' + header + a1 + '</table>';
	
}


function replaceChars(entry) {
	out = ","; // replace this
	add = ""; // with this
	temp = "" + entry; // temporary holder
	
	while (temp.indexOf(out)>-1) {
		pos= temp.indexOf(out);
		temp = "" + (temp.substring(0, pos) + add + 
		temp.substring((pos + out.length), temp.length));	
	}
	
	return temp;
}

function replaceDot(entry) {
	out = "."; // replace this
	add = ""; // with this
	temp = "" + entry; // temporary holder
	
	while (temp.indexOf(out)>-1) {
		pos= temp.indexOf(out);
		temp = "" + (temp.substring(0, pos) + add + 
		temp.substring((pos + out.length), temp.length));	
	}
	
	return temp;
}

function calculate(){
	
	var jumBarang = 10;

	var sub_total = 0;
	var otherAmount = 0;
	var total = 0;
	var tax = 0;
	
	for(i = 1; i<=jumBarang; i++){

		var quantity = eval('document.form1.quantity_' + i +'.value'); 
		var harga_satuan = replaceChars(eval('document.form1.harga_satuan_' + i +'.value')); 
		var harga_total = eval('document.form1.harga_total_' + i +'.value'); 
		
		harga_total = quantity * harga_satuan;

		document.getElementById("harga_total_" + i).value = numeral(harga_total).format('0,0.00');

		sub_total = sub_total + harga_total;
				
	}

	otherAmount = eval(replaceChars(document.form1.otherAmount.value)) + 0;

	document.form1.sub_total.value = numeral(sub_total).format('0,0.00');
	$("#subTotalAmount").text(numeral(sub_total).format('0,0.00'));
	
	tax = eval(sub_total / 10);
	$("#taxAmount").text(numeral(tax).format('0,0.00'));
	
	
	total = sub_total + tax + otherAmount;

	document.form1.total.value = numeral(total).format('0,0.00');
	$("#totalAmount").text(numeral(total).format('0,0.00'));


}


function calculatePO(){
	
	var jumBarang = 10;

	var sub_total = 0;
	var otherAmount = 0;
	var total = 0;
	var tax = 0;
	var ppnStatus = eval('document.form1.inc_ppn.checked');
	
	for(i = 1; i<=jumBarang; i++){

		var quantity = eval('document.form1.quantity_' + i +'.value'); 
		var harga_satuan = replaceChars(eval('document.form1.harga_satuan_' + i +'.value')); 
		var harga_total = eval('document.form1.harga_total_' + i +'.value'); 
		
		harga_total = quantity * harga_satuan;

		document.getElementById("harga_total_" + i).value = numeral(harga_total).format('0,0.00');

		sub_total = sub_total + harga_total
		
	}


	otherAmount = eval(replaceChars(document.form1.otherAmount.value)) + 0;

	document.form1.sub_total.value = numeral(sub_total).format('0,0.00');
	$("#subTotalAmount").text(numeral(sub_total).format('0,0.00'));

	if (ppnStatus == true){	
		tax = eval(sub_total / 10);
	}else{
		tax = 0;
	}
	$("#taxAmount").text(numeral(tax).format('0,0.00'));
	
	
	total = sub_total + tax + otherAmount;

	document.form1.total.value = numeral(total).format('0,0.00');
	$("#totalAmount").text(numeral(total).format('0,0.00'));


}

function calculateAddBarang(){
	
	var jumBarang = 10;
	for(i = 1; i<=jumBarang; i++){

		var quantity = eval('document.form1.quantity_' + i +'.value'); 
		var harga_satuan = replaceChars(eval('document.form1.harga_satuan_' + i +'.value'));
		var harga_total = eval('document.form1.harga_total_' + i +'.value'); 
		
		harga_total = quantity * harga_satuan;
		document.getElementById("harga_total_" + i).value = numeral(harga_total).format('0,0.00');
	}
}

function calculateEdit(){

	var subTotal = eval(replaceChars($("#subTotalAmount").text())) + 0;
	var taxAmount = eval(replaceChars($("#taxAmount").text())) + 0;
	var otherAmount = eval(replaceChars(document.form1.otherAmount.value)) + 0;
//	otherAmount = parseInt(otherAmount);
	
	var total = subTotal+taxAmount+otherAmount;
	$("#totalAmount").text(numeral(total).format('0,0.00'));

}

function calculateEditPO(){

	var subTotal = eval(replaceChars($("#subTotalAmount").text())) + 0;
	var taxAmount = eval(replaceChars($("#taxAmount").text())) + 0;
	var otherAmount = eval(replaceChars(document.form1.otherAmount.value)) + 0;
	var ppnStatus = eval('document.form1.inc_ppn.checked');

	if (ppnStatus == true){	
		taxAmount = eval(subTotal / 10);
	}else{
		taxAmount = 0;
	}
	$("#taxAmount").text(numeral(taxAmount).format('0,0.00'));

	
	var total = subTotal+taxAmount+otherAmount;
	$("#totalAmount").text(numeral(total).format('0,0.00'));

}

function calculateEditBarang(){
	
	var price = eval(replaceChars(document.form1.price.value));
	var quantity = eval(replaceChars(document.form1.quantity.value));
	var total = price * quantity;

	$("#totalAmount").text(numeral(total).format('0,0.00'));
	
}

function calculate_dynamic(){
	
	var form = $("#formSalesOrder"); // or $("form"), or any selector matching an element containing your input fields
	var jumBarang = $("[name='payment_term']", form).val();

//	var jumBarang = document.form1.payment_term.value;
//	alert(jumBarang);

	var sub_total = 0;
	
	for(i = 1; i<=jumBarang; i++){
//		var quantity = eval($("[name='quantity_' + i +']", form).val());
		var harga_satuan = $("[name='harga_satuan_' + i +']", form).val();
		var harga_total = $("[name='harga_total_' + i +']", form).val();

		var quantity = eval('document.form1.quantity_' + i +'.value'); 
		var harga_satuan = eval('document.form1.harga_satuan_' + i +'.value'); 
		var harga_total = eval('document.form1.harga_total_' + i +'.value'); 
		
		harga_total = quantity * harga_satuan;

		document.getElementById("harga_total_" + i).value = numeral(harga_total).format('0,0.00');
//		$("[name='harga_total_' + i +']", form).val() = addCommas(harga_total)
		
		sub_total = sub_total + harga_total
		
	}

	document.form1.sub_total.value = numeral(sub_total).format('0,0.00');
	
//	var biaya_transport = eval(replaceChars(document.form1.biaya_transport.value));
//	var discount = eval(replaceChars(document.form1.discount.value));
//	var total = sub_total + biaya_transport - discount;
	var total = sub_total;

	document.form1.sub_total.value = numeral(total).format('0,0.00');

}

function calculateInvoice(jumBarang){
	
	var jumBarang = jumBarang;

	var sub_total = 0;
	var otherAmount = 0;
	var total = 0;
	var tax = 0;
	var discount = 0;
	var currency = document.form1.currency.value;
	var fxRate = document.form1.fxRate.value;
	
	for(i = 1; i<=jumBarang; i++){

		var isChecked = eval('document.form1.id_do_' + i +'.checked');
		var harga_total = eval('document.form1.do_amount_' + i +'.value'); 

		if (isChecked == true){
			sub_total = parseFloat(sub_total) + parseFloat(harga_total);
		}
	}

	otherAmount = eval(replaceChars(document.form1.otherAmount.value)) + 0;
	discount = eval(replaceChars(document.form1.discount.value)) + 0;
	
	$("#subTotalAmount").text(numeral(sub_total).format('0,0.00'));
//	$("#subTotalAmountIdr").text(numeral(sub_total*fxRate).format('0,0.00'));
	
	tax = eval(sub_total / 10);
	$("#taxAmount").text(numeral(tax).format('0,0.00'));
	
	
	total = sub_total + tax + otherAmount - discount;

	if (currency == "IDR"){
		$("#totalAmount").text(numeral(total).format('0,0.00'));
	}else{
		$("#totalAmount").text(numeral(total).format('0,0.00') + " (" + numeral(total * fxRate).format('0,0.00') + " IDR)");		
		$("#subTotalAmount").text(numeral(sub_total).format('0,0.00') + " (" + numeral(sub_total * fxRate).format('0,0.00') + " IDR)");
		$("#taxAmount").text(numeral(tax).format('0,0.00') + " (" + numeral(tax * fxRate).format('0,0.00') + " IDR)");
	}
	
	document.form1.sub_total.value = sub_total;
	document.form1.total.value = total;

}


function calculateInvoicePO(jumBarang){
	
	var jumBarang = jumBarang;
	var sub_total = 0;
	var otherAmount = 0;
	var total = 0;
	var tax = 0;
	var currency = document.form1.currency.value;
	var fxRate = document.form1.fxRate.value;
	
	for(i = 1; i<=jumBarang; i++){

		var isChecked = eval('document.form1.id_do_' + i +'.checked');
		var harga_total = eval('document.form1.do_amount_' + i +'.value'); 

		if (isChecked == true){
			sub_total = parseFloat(sub_total) + parseFloat(harga_total);
		}
	}

	$("#subTotalAmount").text(numeral(sub_total).format('0,0.00'));
	tax = eval(sub_total / 10);
	$("#taxAmount").text(numeral(tax).format('0,0.00'));
	
	total = sub_total + tax;

	if (currency == "IDR"){
		$("#totalAmount").text(numeral(total).format('0,0.00'));
	}else{
		$("#totalAmount").text(numeral(total).format('0,0.00') + " (" + numeral(total * fxRate).format('0,0.00') + " IDR)");		
		$("#subTotalAmount").text(numeral(sub_total).format('0,0.00') + " (" + numeral(sub_total * fxRate).format('0,0.00') + " IDR)");
		$("#taxAmount").text(numeral(tax).format('0,0.00') + " (" + numeral(tax * fxRate).format('0,0.00') + " IDR)");
	}
	
	document.form1.sub_total.value = sub_total;
	document.form1.total.value = total;

}

function calculatePelunasan(jumInvoice){
	
	var jumInvoice = jumInvoice;
	var invoiceAmount = 0;
	var invoiceAmountDiBayar = 0;
	var sisa = 0;
	var totalDiBayar = 0;
	var totalBelumDiBayar = eval(replaceChars(document.form1.totalBelumDiBayar.value)) + 0;
	var otherAmount = eval(replaceChars(document.form1.otherAmount.value)) + 0;

	for(i = 1; i<=jumInvoice; i++){
		
		var isChecked = eval('document.form1.inv_' + i +'.checked');
		if (isChecked == true){
			
			invoiceAmountSudahDiBayar = replaceChars(eval('document.form1.amount_sudahDiBayar_' + i +'.value')); 
			invoiceAmount = replaceChars(eval('document.form1.amount_inv_' + i +'.value')); 
			invoiceAmountDiBayar = replaceChars(eval('document.form1.amount_diBayar_' + i +'.value'));
			sisa = invoiceAmount - invoiceAmountDiBayar - invoiceAmountSudahDiBayar; 
			totalDiBayar = parseFloat(totalDiBayar) + parseFloat(invoiceAmountDiBayar);
//			totalBelumDiBayar = parseFloat(totalBelumDiBayar) + parseFloat(sisa);		
			$("#amount_sisa_" + i).text(numeral(sisa).format('0,0.00'));
			
		}else{
			invoiceAmountSudahDiBayar = replaceChars(eval('document.form1.amount_sudahDiBayar_' + i +'.value')); 
			invoiceAmount = replaceChars(eval('document.form1.amount_inv_' + i +'.value')); 
			invoiceAmountDiBayar = replaceChars(eval('document.form1.amount_diBayar_' + i +'.value'));
			sisa = invoiceAmount - invoiceAmountDiBayar - invoiceAmountSudahDiBayar; 
//			totalDiBayar = parseFloat(totalDiBayar) + parseFloat(invoiceAmountDiBayar);
//			totalBelumDiBayar = parseFloat(totalBelumDiBayar) + parseFloat(sisa);		
			totalBelumDiBayar = parseFloat(totalBelumDiBayar);		
			$("#amount_sisa_" + i).text(numeral(sisa).format('0,0.00'));
			
		}
		
	}
	$("#totalDiBayar").text(numeral(totalDiBayar + otherAmount).format('0,0.00'));
	$("#totalBelumDiBayar").text(numeral(totalBelumDiBayar - totalDiBayar).format('0,0.00'));
	document.form1.total.value = totalDiBayar + otherAmount;

}


function calculateInvoiceEdit(){
	
	var sub_total = replaceChars(eval('document.form1.sub_total.value')); 
	var tax = replaceChars(eval('document.form1.tax.value')); 
	var other_amount = replaceChars(eval('document.form1.otherAmount.value')); 
	var discount = replaceChars(eval('document.form1.discount.value')); 

	var total = parseFloat(sub_total) + parseFloat(tax) + parseFloat(other_amount) - parseFloat(discount);
	$("#totalAmount").text(numeral(total).format('0,0.00'));	
}


function calculateFromCash(){
	
	var jumItem = 10;

	var sub_total = 0;
	var otherAmount = 0;
	var total = 0;
	
	for(i = 1; i<=jumItem; i++){

		var harga_total = parseFloat(replaceChars(eval('document.form1.amount_' + i +'.value')));
		sub_total = parseFloat(sub_total) + parseFloat(harga_total);
		
	}

//	otherAmount = eval(replaceChars(document.form1.otherAmount.value)) + 0;
//	total = parseFloat(sub_total) + parseFloat(otherAmount);

	total = parseFloat(sub_total);

	document.form1.total.value = numeral(total).format('0,0.00');
	$("#totalAmount").text(numeral(total).format('0,0.00'));


}


// NUMBER FORMAT JAVASCRIPT START

function addCommas(str) {
    var amount = new String(str);
    amount = amount.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = ',' + output;
    }
    return output;
}

function addDot(str) {
    var amount = new String(str);
    amount = amount.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = ',' + output;
    }
    return output;
}


function confirmBeforeDelete()
{
	
	var r=confirm("Pastikan data yang akan di DELETE sudah benar","Ya");
	if (r==true){

		var s=confirm("Delete Last Alert, Anda Yakin?","Ya");
		if (s==true){
			return true;
		}else{
			return false;		
		}

	}else{
    	return false;

	}
	
	return false;
}


function confirmAmount()
{
	var curr = document.form1.id_currency.value;
	var total = replaceChars(document.form1.total.value);
	var jumBarang = 10;
	
//	alert(curr + ' - ' + total);

	if((total >= 100000) && (curr == 'USD')){

		var s=confirm("Amount " + total + " Terlalu Besar Untuk Currency " + curr + ", Anda Yakin?");
		if (s==true){
			return true;
		}else{
			return false;		
		}		
		
	}else if((total <= 100000) && (curr == 'IDR')){

		var s=confirm("Amount " + total + " Terlalu Kecil Untuk Currency " + curr + ", Anda Yakin?");
		if (s==true){
			return true;
		}else{
			return false;		
		}		
		
	}

	for(i = 1; i<=jumBarang; i++){
		var barang_sales = eval('document.form1.barang_sales_id_' + i +'.value'); 		
		
		for(j = 1; j<=i; j++){
			if((barang_sales != "") && (i != j)){
				var barang_sebelumnya = eval('document.form1.barang_sales_id_' + j +'.value');
//				console.log(i + '. ' + barang_sales + ' -- ' + j + '. ' + barang_sebelumnya);
				if(barang_sales == barang_sebelumnya){
					alert("Barang Yang Sama Tidak Boleh Di Pilih Lebih Dari Sekali");
					return false;	
				}
			}
		}
	}



	
	return true;
	
}