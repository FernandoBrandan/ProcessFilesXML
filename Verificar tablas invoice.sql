DECLARE

cantidad_line number:=0;
cantidad_afip number:=0;
cantidad_payment number:=0;
cantidad_multisam number:=0;
cantidad_item number:=0;
cantidad_vat number:=0;
cantidad_tax NUMBER:=0;


l_invoice_no VARCHAR2(20);
l_invoice_type VARCHAR2(20);
l_till_no VARCHAR2(20);

 
CURSOR principal 
IS
SELECT INVOICE_NO, 
	   INVOICE_TYPE, 
	   TILL_NO 
FROM INVOICE;
p_rec principal%ROWTYPE;


CURSOR line(in_no in number, in_type in number, till in number) IS
select * from INVOICE_LINE  			
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       line_rec line%ROWTYPE;

CURSOR afip(in_no in number, in_type in number, till in number) IS
select * from INVOICE_AFIP  			
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       afip_rec afip%ROWTYPE;

CURSOR payment(in_no in number, in_type in number, till in number) IS
select * from INVOICE_PAYMENT  		
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       payment_rec payment%ROWTYPE;

CURSOR multisam(in_no in number, in_type in number, till in number) IS
select * from INVOICE_MULTISAM  		
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       multisam_rec multisam%ROWTYPE;

CURSOR item(in_no in number, in_type in number, till in number) IS
select * from INVOICE_PAYMENT_ITEM 	
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       item_rec item%ROWTYPE;

CURSOR vat(in_no in number, in_type in number, till in number) IS
select * from INVOICE_VAT 		 		
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       vat_rec vat%ROWTYPE;

CURSOR tax(in_no in number, in_type in number, till in number) IS
select * from INVOICE_TAX		 		
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       tax_rec tax%ROWTYPE;

BEGIN
	
 OPEN principal; 
 LOOP
	 FETCH principal INTO p_rec;
		 EXIT WHEN principal%NOTFOUND; -- Último registro.
		 
			  OPEN line(p_rec.invoice_no, p_rec.invoice_type, p_rec.till_no);
		       LOOP
		         FETCH line INTO line_rec;
		         EXIT WHEN line%NOTFOUND; -- Último registro.
		         cantidad_line := cantidad_line + 1;
		       END LOOP;  
      			 CLOSE line;
		            
	           OPEN afip(p_rec.invoice_no, p_rec.invoice_type, p_rec.till_no);
		       LOOP
		         FETCH afip INTO afip_rec;
		         EXIT WHEN afip%NOTFOUND; -- Último registro.
		         cantidad_afip := cantidad_afip + 1;
		       END LOOP;  
      			 CLOSE afip;
		            
	           OPEN payment(p_rec.invoice_no, p_rec.invoice_type, p_rec.till_no);
		       LOOP
		         FETCH payment INTO payment_rec;
		         EXIT WHEN payment%NOTFOUND; -- Último registro.
		         cantidad_payment := cantidad_payment + 1;
		       END LOOP;  
      			 CLOSE payment;
		            
	           OPEN multisam(p_rec.invoice_no, p_rec.invoice_type, p_rec.till_no);
		       LOOP
		         FETCH multisam INTO multisam_rec;
		         EXIT WHEN multisam%NOTFOUND; -- Último registro.
		         cantidad_multisam := cantidad_multisam + 1;
		       END LOOP;  
      			 CLOSE multisam;
		            
	           OPEN item(p_rec.invoice_no, p_rec.invoice_type, p_rec.till_no);
		       LOOP
		         FETCH item INTO item_rec;
		         EXIT WHEN item%NOTFOUND; -- Último registro.
		         cantidad_item := cantidad_item + 1;
		       END LOOP;  
      			 CLOSE item;
		            
	           OPEN vat(p_rec.invoice_no, p_rec.invoice_type, p_rec.till_no);
		       LOOP
		         FETCH vat INTO vat_rec;
		         EXIT WHEN vat%NOTFOUND; -- Último registro.
		         cantidad_vat := cantidad_vat + 1;
		       END LOOP;  
      			 CLOSE vat;
		            
	           OPEN tax(p_rec.invoice_no, p_rec.invoice_type, p_rec.till_no);
	           LOOP
		         FETCH tax INTO tax_rec;
		         EXIT WHEN tax%NOTFOUND; -- Último registro	.
		         cantidad_tax := cantidad_tax + 1;
		       END LOOP;  
      			 CLOSE tax; 
      			
      			
		DBMS_OUTPUT.PUT_LINE ('invoice_no: ' || p_rec.invoice_no  || '- invoice_type:' || p_rec.invoice_type || ' - till_no: ' || p_rec.till_no);
		DBMS_OUTPUT.PUT_LINE ('line: ' || cantidad_line);
		DBMS_OUTPUT.PUT_LINE ('afip: ' || cantidad_afip);
		DBMS_OUTPUT.PUT_LINE ('payment: ' || cantidad_payment);
		DBMS_OUTPUT.PUT_LINE ('item: ' || cantidad_multisam);
		DBMS_OUTPUT.PUT_LINE ('vat: ' || cantidad_item);
		DBMS_OUTPUT.PUT_LINE ('tax: ' || cantidad_tax); 
		DBMS_OUTPUT.PUT_LINE ('-------'); 
	 END LOOP; 
 CLOSE principal;
END;