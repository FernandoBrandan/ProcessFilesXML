/*Script para verificar ingresos */

/***********************************
	ATENCION!!! 
	VERIFICAR QUE EL PROCEDURE
	PARA BORRAR ESTE COMENTADO
**************************************/

DECLARE 

l_invoice_no VARCHAR2(20);
l_invoice_type VARCHAR2(20);
l_till_no VARCHAR2(20);

/*Buscar varios*/
type array_t is varray(4000) of varchar2(20);
 array array_t := array_t(
   /* Separar num_num.num */
   /* invoince_no-invoince_type.till_no */

'162818-5.7',
'162819-5.7',
'162820-5.7',
'162821-5.7',
'162822-5.7',
'162823-5.7',
'162824-5.7',
'162825-5.7',
'162826-5.7',
'162827-5.7',
'162828-5.7',
'162829-5.7',
'162830-5.7',
'162831-5.7',
'162833-5.7',
'162834-5.7',
'162835-5.7',
'162836-5.7',
'162837-5.7',
'162838-5.7',
'162839-5.7', 
'162613-5.7', 
'162840-5.7',
'162841-5.7',
'162842-5.7',
'162843-5.7',
'162844-5.7',
'162845-5.7',
'162846-5.7',
'162847-5.7',
'162848-5.7',
'162849-5.7', 
'162850-5.7',
'162851-5.7',
'162852-5.7',
'162853-5.7',
'162854-5.7',
'162855-5.7',
'162856-5.7',
'162857-5.7',
'162858-5.7',
'162859-5.7', 
'162860-5.7',
'162861-5.7',
'162862-5.7',
'162863-5.7', 
'162864-5.7',
'162865-5.7',
'162866-5.7',
'162867-5.7',
'162868-5.7',
'162869-5.7',
'162870-5.7',
'162871-5.7',
'162872-5.7',
'162873-5.7',
'162875-5.7',
'162876-5.7',
'162877-5.7',
'162878-5.7',
'162879-5.7',
'162880-5.7',
'162881-5.7',
'162882-5.7',
'162883-5.7',
'162884-5.7',
'162885-5.7',
'162886-5.7' 
   );
 
  
CURSOR invoice(in_no in number, in_type in number, till in number)  IS
select * from INVOICE  				
where invoice_no= in_no  and invoice_type = in_type and till_no = till;
       invoice_rec invoice%ROWTYPE;

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
      
      
/**********************/
/*	Procedure		  */
/**********************/       
PROCEDURE Borrar_registros(pr_in_no number, pr_in_type NUMBER , pr_till_no NUMBER) IS 
BEGIN
	  dbms_output.put_line('Borrando registros');
		delete INVOICE 		 		where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
		delete INVOICE_LINE  		where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
		delete INVOICE_AFIP  		where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
		delete INVOICE_PAYMENT  	where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
		delete INVOICE_MULTISAM 	where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
		delete INVOICE_PAYMENT_ITEM where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
		delete INVOICE_VAT  		where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
		delete INVOICE_TAX  		where invoice_no= pr_in_no and invoice_type = pr_in_type and till_no = pr_till_no;
END;
        
PROCEDURE Borrar_Todo(pr_in_no number) as 
BEGIN
	  	dbms_output.put_line('Borrando TODOS los datos');
		delete INVOICE;
		delete INVOICE_LINE;
		delete INVOICE_AFIP;
		delete INVOICE_PAYMENT ; 
		delete INVOICE_MULTISAM ;
		delete INVOICE_PAYMENT_ITEM;
		delete INVOICE_VAT;
		delete INVOICE_TAX;
END;



/**********************/
/*	PRINCIPAL		  */
/**********************/  
BEGIN

	 for i in 1..array.count loop
	      	 dbms_output.put_line('Busqueda: ' || array(i));
	           l_invoice_no := substr(array(i), 1, instr(array(i), '-')-1);
	           l_invoice_type := substr(array(i), instr(array(i), '-')+1, 1);
	           l_till_no := substr(array(i), instr(array(i), '.')+1, length(array(i)));
	           
	           dbms_output.put_line(l_invoice_no ||' - ' || l_invoice_type  || ' - '||  l_till_no);
	      
		            
	           OPEN invoice(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH invoice INTO invoice_rec;
		         EXIT WHEN invoice%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('invoice: '|| invoice_rec.invoice_no || ' ' || invoice_rec.invoice_type || ' ' || invoice_rec.till_no);
		       END LOOP;  
      			 CLOSE invoice;
		            
	           OPEN line(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH line INTO line_rec;
		         EXIT WHEN line%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('line: '||line_rec.invoice_no || ' ' || line_rec.invoice_type || ' ' || line_rec.till_no);
		       END LOOP;  
      			 CLOSE line;
		            
	           OPEN afip(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH afip INTO afip_rec;
		         EXIT WHEN afip%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('afip: '||afip_rec.invoice_no || ' ' || afip_rec.invoice_type || ' ' || afip_rec.till_no);
		       END LOOP;  
      			 CLOSE afip;
		            
	           OPEN payment(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH payment INTO payment_rec;
		         EXIT WHEN payment%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('payment: '||payment_rec.invoice_no || ' ' || payment_rec.invoice_type || ' ' || payment_rec.till_no);
		       END LOOP;  
      			 CLOSE payment;
		            
	           OPEN multisam(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH multisam INTO multisam_rec;
		         EXIT WHEN multisam%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('multisam: '||multisam_rec.invoice_no || ' ' || multisam_rec.invoice_type || ' ' || multisam_rec.till_no);
		       END LOOP;  
      			 CLOSE multisam;
		            
	           OPEN item(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH item INTO item_rec;
		         EXIT WHEN item%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('item: '||item_rec.invoice_no || ' ' || item_rec.invoice_type || ' ' || item_rec.till_no);
		       END LOOP;  
      			 CLOSE item;
		            
	           OPEN vat(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH vat INTO vat_rec;
		         EXIT WHEN vat%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('vat: '||vat_rec.invoice_no || ' ' || vat_rec.invoice_type || ' ' || vat_rec.till_no);
		       END LOOP;  
      			 CLOSE vat;
		            
	           OPEN tax(l_invoice_no, l_invoice_type, l_till_no);
		       LOOP
		         FETCH tax INTO tax_rec;
		         EXIT WHEN tax%NOTFOUND; -- Último registro.
		         DBMS_OUTPUT.PUT_LINE ('tax: '||tax_rec.invoice_no || ' ' || tax_rec.invoice_type || ' ' || tax_rec.till_no);
		       END LOOP;  

      			 CLOSE tax;      
		      
		 DBMS_OUTPUT.PUT_LINE ('');
		 DBMS_OUTPUT.PUT_LINE ('----------------------------------------------------------');
	    --Borrar_registros( l_invoice_no, l_invoice_type, l_till_no);
		--Borrar_Todo( l_invoice_no);
	end loop; 

		 DBMS_OUTPUT.PUT_LINE ('Fin ejecucion'); 

END;