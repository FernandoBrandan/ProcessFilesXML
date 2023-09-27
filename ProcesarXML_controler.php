<?php

namespace App\Http\Controllers; 
 
use App\Store;
use DB;
use Sabberworm\CSS\Value\Size;
use App\Exceptions\Handler;
 
class ProcesarXML extends Controller
{
    public function principal()
    {         
        $rutaTo = storage_path()  . "/to";
        $rutaFrom = storage_path()  . "/from";
        $contTo = 0;
        $contFrom = 0; 
        $contProcesados = 0;
        $contNoProcesados = 0; 

        if (is_dir($rutaTo) && is_dir($rutaFrom)){ 
            $gestorTo = opendir($rutaTo);
            $gestorFrom = opendir($rutaFrom); 
            while (($archivo = readdir($gestorTo)) !== false)  { 
                if ($archivo != "." && $archivo != "..") {
                    $ruta_completaTO[] = $rutaTo . "/" . $archivo; 
                    $contTo = $contTo + 1 ;  
                }
            }
            while (($archivo = readdir($gestorFrom)) !== false)  { 
                if ($archivo != "." && $archivo != "..") {
                    $ruta_completaFrom[] = $rutaFrom . "/" . $archivo;    
                    $contFrom = $contFrom + 1 ;  
                } 
            }   
        }   
 
        if($contFrom ==0 && $contTo ==0){
            echo "No hay archivos para procesar";
        }
        elseif($contFrom == $contTo){
            for ($i=0; $i < count($ruta_completaTO); $i++) {   
                try{                       
                    print_r($ruta_completaTO[$i]);
                    echo ('<br>'); 
                //    echo head((string)$ruta_completaTO[$i], (string)$ruta_completaFrom[$i]);  
                    echo ('<br>'); 
                //    echo lineas((string)$ruta_completaTO[$i]);  
                    echo ('<br>'); 
                //     echo payment((string)$ruta_completaTO[$i]);
                    echo ('<br>');  
               //    echo multisam((string)$ruta_completaTO[$i]);
                    echo ('<br>'); 
                //    echo vat((string)$ruta_completaTO[$i]);
                    echo ('<br>'); 
                //     echo tax((string)$ruta_completaTO[$i]); 
                    echo ('<br>');  
                //    echo multisam1X2((string)$ruta_completaTO[$i]);
                    echo ('<br>');      
                    echo vat1X2((string)$ruta_completaTO[$i]);                                        
                    echo ('<br>');
                    echo ('<br>');                           
                    $contProcesados = $contProcesados + 1;

                } catch (\Throwable $th) { 
                    print_r($ruta_completaTO[$i]);
                    echo ('<br>');
                    echo ('<br>');
                    print_r("Error al procesar archivos" . " | -- | " . $ruta_completaTO[$i] . " | -- | " . $th);
                    echo ('<br>');
                    echo ('<br>');              
                    $contNoProcesados = $contNoProcesados + 1;
                }  
            }
        }
        else{
            echo "Diferencia de archivos";
        }  

        DB::commit();       
        echo "Archivos procesados: " . $contProcesados; 
        
        return view('ProcesarXML');
        
    }         
} 

function head(string $to, string $from){    

    try { 

    $xmlStringTo = file_get_contents($to);
    $xmlObjectTo = simplexml_load_string($xmlStringTo);       
    $jsonTo = json_encode($xmlObjectTo);
    $arrayTo = json_decode($jsonTo, true); 

    $xmlStringFrom = file_get_contents($from);
    $xmlObjectFrom = simplexml_load_string($xmlStringFrom);       
    $jsonFrom = json_encode($xmlObjectFrom);
    $arrayFrom = json_decode($jsonFrom, true);    

    $qryheadTo="insert into invoice(till_no,invoice_no,invoice_type,cashier_no,invoice_date,invoice_time,
    nbr_lines,nbr_void_lines,tot_nm_food,tot_nm_nfood,tot_mm_food,
    tot_mm_nfood,fee_amount,fee_status,PROC_IND,cust_no,
    store_no,bonus_points,till_status,cons_mode,tot_pack,
    cash_change,rounding_diff,ttax_cond,supervisor_cd,
    inv_status,fisc_pos_id,fisc_invoice_no,fisc_inv_no_end,orig_fisc_pos_id,
    orig_fisc_inv_no,ORG_TILL_NO,ORG_INVOICE_NO,VOUCHER_NO) values( ";  
    
    $qryInvoiceFrom = "insert into invoice_afip(till_no,invoice_no,invoice_type,  invoice_date_due,
                     type_cae_caea, cae_caea_no, comprobante_no) values ("; 

    $valortax = $arrayTo ['head']['ttax_cond']; 
    if($valortax == null){    
        $tax = 0;
    }
    else{
        $tax = $arrayTo ['head']['ttax_cond'];
    }

    $caeCaea = 0;  
    $caeCaeaValor = 0;            
    $valuesTo =  $arrayTo ['head']['till_no'] . ", " .
                $arrayTo ['head']['invoice_no'] . ", " . 
                $arrayTo ['head']['invoice_type'] . ", " . 
                $arrayTo ['head']['cashier_no'] . ", " .
                "to_date('" . $arrayTo ['head']['invoice_date'] . "', 'YYYYMMDD')" . ", " .
                $arrayTo ['head']['invoice_time'] . ", " .
                $arrayTo ['head']['nbr_lines'] . ", " .
                $arrayTo ['head']['nbr_void_lines'] . ", " .
                quitarDecimales($arrayTo ['head']['tot_nm_food']) . ", " .
                quitarDecimales($arrayTo ['head']['tot_nm_nfood']) . ", " .
                quitarDecimales($arrayTo ['head']['tot_mm_food']) . ", " .
                quitarDecimales($arrayTo ['head']['tot_mm_nfood']) . ", " .
                quitarDecimales($arrayTo ['head']['fee_amount']) . ", " .
                $arrayTo ['head']['fee_status'] . ", " .
                0 . ", " .
                $arrayTo ['head']['cust_no'] . ", " .
                $arrayTo ['head']['store_no'] . ", " .
                0 . ", " .
                3 . ", " .
                0 . ", " .
                0 . ", " .
                0 . ", " .
                quitarDecimales($arrayTo ['head']['rounding_diff']) . ", " .
            "'" . $tax . "', " .
            "'" . 0 . "', " .  
                "'O'" . ", " .
                $arrayFrom ['puntoVenta'] . ", " .
                $arrayFrom ['numeroComprobante'] . ", " . 
                $arrayTo ['head']['fisc_inv_no_end'] . ", " .
                0 . ", " .
                0 . ", " .
                0 . ", " .
                0 . ", " .
                0;  
 
    
    $valor1 = $arrayFrom ['cae']; 
  
    if($valor1 != null){    
        $caeCaea = 1;   
        $caeCaeaValor = $arrayFrom ['cae'];
    }
    else{      
            $caeCaea = 0;     
            $caeCaeaValor = $arrayFrom ['caea'];            
    } 
   
    
    $valuesfrom =  $arrayTo ['head']['till_no'] . ", " .
                    $arrayTo ['head']['invoice_no'] . ", " .
                    $arrayTo ['head']['invoice_type'] . ", " . 
      "to_date('" . $arrayFrom ['caeFechaVto'] . "', 'YYYY-MM-DD') " . ", " .  
                    $caeCaea . ", " .
              "'" . $caeCaeaValor . "'," .
                    $arrayFrom ['numeroComprobante'] ; 
                    
    $InsertTo = $qryheadTo . $valuesTo . ")";   
    $InsertFrom = $qryInvoiceFrom . $valuesfrom . ")";  

    $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertTo);  
    $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertFrom);  

    return "Finalizo correctamente HEAD/AFIP";
    
    } catch (\Throwable $th) {
        return "Error al procesar HEAD/AFIP";
    }

}

function lineas(string $to){
     
    try{
    $xmlString = file_get_contents($to);
    $xmlObject = simplexml_load_string($xmlString);      
 
    $json = json_encode($xmlObject);
    $array = json_decode($json, true);   
    
    $qryLineas = "insert into invoice_line(till_no,invoice_no,invoice_type,art_no,
                    seq_no,art_ind,qty,amount,disc_amount,
                    mmail_no,vat_no,barcode,art_grp_no,msam_disc1,
                    msam_mmail_no1,msam_maction_no1,msam_disc2,msam_mmail_no2,msam_maction_no2,LEGAL_BASE)
                    values("; 
    $cont = 0;
    $unico = false;   
  
    foreach ($array as $key => $valor){
        if ($key == "lines"){
            foreach ($valor as $reg){  
                $tag = (array)$reg;   
                if(is_array($tag) && count($tag)==24){    
                    $unico = true;     

                    try {       
                        $DatosLinea  = $tag ["till_no"] . ", " .
                                    $tag ["invoice_no"] .   ", " .
                                    $tag ["invoice_type"] .   ", " .
                                    $tag ["art_no"] .   ", " . 
                                    $tag ["seq_no"] .   ", " .
                                    $tag ["art_ind"] .   ", " .
                                    quitarDecimales($tag ["qty"]) .   ", " .
                                    quitarDecimales($tag ["amount"]) .   ", " .
                                    quitarDecimales($tag ["disc_amount"]) .   ", " .
                                    $tag ["mmail_no"] .   ", " .
                                    $tag ["vat_no"] .   ", " . 
                                 //   "'" . $tag ["barcode"] .   "', " .
                                    "'" .    Nulos($tag ['barcode']) .   "', " .
                                    $tag ["art_grp_no"] .   ", " . 
                                    quitarDecimales($tag ["msam_disc1"]) .   ", " .
                                    $tag ["msam_mmail_no1"] .   ", " .
                                    $tag ["msam_maction_no1"] .   ", " .
                                    quitarDecimales($tag ["msam_disc2"]) .   ", " .
                                    $tag ["msam_mmail_no2"] .   ", " .
                                    $tag ["msam_maction_no2"]  .   ", " . 0 ;                           
                    }
                    catch (\Exception $e) {  
                        $unico = false;    
                        foreach ($reg as $id){
                            
                            ${"DatosLineas" . $cont} = $id ['till_no'] . ", " .
                                                        $id ['invoice_no'] .   ", " .
                                                        $id ['invoice_type'] .   ", " .
                                                        $id ['art_no'] .   ", " . 
                                                        $id ['seq_no'] .   ", " .
                                                        $id ['art_ind'] .   ", " .
                                                        quitarDecimales($id ['qty']) .   ", " .
                                                        quitarDecimales($id ['amount']) .   ", " .
                                                        quitarDecimales($id ['disc_amount']) .   ", " .
                                                        $id ['mmail_no'] .   ", " .
                                                        $id ['vat_no'] .   ", " . 
                                                  //      "'" . $id ['barcode'] .   "', " .
                                                  "'" .  Nulos($id ['barcode']) .   "', " .
                                                        $id ['art_grp_no'] .   ", " . 
                                                        quitarDecimales($id ['msam_disc1']) .   ", " .
                                                        $id ['msam_mmail_no1'] .   ", " .
                                                        $id ['msam_maction_no1'] .   ", " .
                                                        quitarDecimales($id ['msam_disc2']) .   ", " .
                                                        $id ['msam_mmail_no2'] .   ", " .
                                                        $id ['msam_maction_no2']  .   ", " . 0 ;
                            $cont = $cont + 1; 
                        }
                    } 
                }
                else{ 
                    foreach ($reg as $id){
                        ${"DatosLineas" . $cont} = $id ['till_no'] . ", " .
                                                    $id ['invoice_no'] .   ", " .
                                                    $id ['invoice_type'] .   ", " .
                                                    $id ['art_no'] .   ", " . 
                                                    $id ['seq_no'] .   ", " .
                                                    $id ['art_ind'] .   ", " .
                                                    quitarDecimales($id ['qty']) .   ", " .
                                                    quitarDecimales($id ['amount']) .   ", " .
                                                    quitarDecimales($id ['disc_amount']) .   ", " .
                                                    $id ['mmail_no'] .   ", " .
                                                    $id ['vat_no'] .   ", " . 
                                               //     "'" . $id ['barcode'] .   "', " .
                                                    "'" .     Nulos($id ['barcode'])  .   "', " .
                                                    $id ['art_grp_no'] .   ", " . 
                                                    quitarDecimales($id ['msam_disc1']) .   ", " .
                                                    $id ['msam_mmail_no1'] .   ", " .
                                                    $id ['msam_maction_no1'] .   ", " .
                                                    quitarDecimales($id ['msam_disc2']) .   ", " .
                                                    $id ['msam_mmail_no2'] .   ", " .
                                                    $id ['msam_maction_no2']  .   ", " . 0 ;
                        $cont = $cont + 1; 
                    }
                }
            }
        }
    }
    if($unico){
        $InsertLineas= $qryLineas . $DatosLinea . ")"; 
        $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertLineas); 
    }   
    else{
        for ($i=0; $i < $cont; $i++) {      
            ${"InsertLineas" . $i} = $qryLineas . ${"DatosLineas" . $i} . "0)";  
        }
        for ($i=0; $i < $cont; $i++) { 
            $datos = DB::connection(Store::getStoreDatabase(6))->insert(${"InsertLineas" . $i}); 
        }  
    }

    return "Finalizo correctamente LINEAS";
    
    } catch (\Throwable $th) {
        return "Error al procesar LINEAS";
    }   
} 

function payment(string $to){

    try{
    $xmlString = file_get_contents($to);
    $xmlObject = simplexml_load_string($xmlString);      
 
    $json = json_encode($xmlObject);
    $array = json_decode($json, true);   
    
    $qryPayment = "insert into invoice_payment (till_no, invoice_no, invoice_type, paym_cd, paym_amount, 
                    local_paym_amount, standard, rate, paym_extra_amount) values (";
    $cont = 0;    
    $unico = false; 

    foreach ($array as $key => $valor){
        if($key == "payments"){      
            foreach ($valor as  $reg){  
                $tag = (array)$reg;     
                if(is_array($reg) && count($reg)==11){   
                    $unico = true;   
                    try{  
                        $DatosPayments = $tag["till_no"] . ", " .
                                            $tag["invoice_no"] . ", " .
                                            $tag["invoice_type"] .   ", " .
                                            $tag["paym_cd"] .   ", " .
                                            $tag["paym_amount"] .   ", " .
                                            $tag["local_paym"] .   ", " .
                                            $tag["standard"] .   ", " .
                                            $tag["rate"] .   ", " .
                                            $tag["paym_extra_amount"] ; 
                    } 
                    catch (\Exception $e) {  
                        $unico = false;  
                        foreach ($reg as $id){ 
                            ${"DatosPayments" . $cont} = $id ['till_no'] . ", " .
                                                        $id ['invoice_no'] . ", " .
                                                        $id ['invoice_type'] .   ", " .
                                                        $id ['paym_cd'] .   ", " .
                                                        quitarDecimales($id ['paym_amount']) .   ", " .
                                                        quitarDecimales($id ['local_paym']) .   ", " .
                                                        $id ['standard'] .   ", " .
                                                        quitarDecimales($id ['rate']) .   ", " .
                                                        quitarDecimales($id ['paym_extra_amount']) ;  
                            $cont = $cont + 1;    
                        }   
                    }
                }
                else{ 
                    foreach ($reg as $id){ 
                        ${"DatosPayments" . $cont} = $id ['till_no'] . ", " .
                                                    $id ['invoice_no'] . ", " .
                                                    $id ['invoice_type'] .   ", " .
                                                    $id ['paym_cd'] .   ", " .
                                                    quitarDecimales($id ['paym_amount']) .   ", " .
                                                    quitarDecimales($id ['local_paym']) .   ", " .
                                                    $id ['standard'] .   ", " .
                                                    quitarDecimales($id ['rate']) .   ", " .
                                                    quitarDecimales($id ['paym_extra_amount']) ;  
                        $cont = $cont + 1;    
                    } 
                }        
            } 
        }         
    } 
 
    if($unico)
    { 
        $InsertPayments = $qryPayment . $DatosPayments . ")"; 
        $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertPayments);  
    }   
    else{
        for ($i=0; $i < $cont; $i++) {      
            ${"InsertPayments" . $i} = $qryPayment . ${"DatosPayments" . $i} . "0)";  
        }
        for ($i=0; $i < $cont; $i++) { 
            $datos = DB::connection(Store::getStoreDatabase(6))->insert(${"InsertPayments" . $i}); 
        }  
    }
    return "Finalizo correctamente PAYMENT";
    
    } catch (\Throwable $th) {
        return "Error al procesar PAYMENT";
    } 
}

function multisam(string $to){
 
    try
    {
        $xmlString = file_get_contents($to);
        $xmlObject = simplexml_load_string($xmlString);      
    
        $json = json_encode($xmlObject);
        $array = json_decode($json, true);     
        
        $qryMultisam1 = "insert into invoice_multisam (till_no, invoice_no, invoice_type, mmail_no, 
                        maction_no, makro_action_type, version_no, threshold_qty, result, result_type) values (";
        
        $qryMultisam2 = "insert into invoice_payment_item (till_no,invoice_no, invoice_type, paym_cd, 
                                seq_no, id, paym_date, paym_amount) values (";
        $cont = 0;   
        $unico = false;  

        foreach ($array as $key => $valor){
            if($key == "multisams"){    
                foreach ($valor as $reg){    
                    $tag = (array)$reg;
                    $tam = count($tag);  
                    if(is_array($tag) && $tam==14){    
                        $unico = true;    
                        try {    
                            $DatosMultisam1 = $tag ['till_no'] . ", " . 
                                            $tag ['invoice_no'] . ", " . 
                                            $tag ['invoice_type'] . ", " . 
                                            $tag ['mmail_no'] . ", " . 
                                            $tag ['maction_no'] . ", " . 
                                            $tag ['maction_type'] . ", " . 
                                            $tag ['version_no'] . ", " . 
                                            $tag ['threshold_qty'] . ", " . 
                                            0 . ", " . 
                                            $tag ['result_type'];                        
                        }
                        catch(\Exception $e){ 
                            $unico = false;   
                            try{
                                foreach ($reg as $id){ 
                                    ${"DatosMultisam1_" . $cont} = $id ['till_no'] . ", " . 
                                                                    $id ['invoice_no'] . ", " . 
                                                                    $id ['invoice_type'] . ", " . 
                                                                    $id ['mmail_no'] . ", " . 
                                                                    $id ['maction_no'] . ", " . 
                                                                    $id ['maction_type'] . ", " . 
                                                                    $id ['version_no'] . ", " . 
                                                                    $id ['threshold_qty'] . ", " . 
                                                                    0 . ", " . 
                                                                    $id ['result_type'];
                                $cont = $cont + 1;                        
                                }
                            }catch (\Throwable $th) {
                                for ($i=0; $i < $tam ; $i++) {  
                                    if($tam ==  $i+1){    
                                        try {
                                            ${"DatosMultisam1_" . $cont} =  $reg[$i]['till_no'][0] . ", " .
                                                                        $reg[$i] ['invoice_no'][0] . ", " . 
                                                                        $reg[$i] ['invoice_type'][0] . ", " . 
                                                                        $reg[$i] ['mmail_no'] . ", " . 
                                                                        $reg[$i] ['maction_no'] . ", " . 
                                                                        $reg[$i] ['maction_type'] . ", " . 
                                                                        $reg[$i] ['version_no'] . ", " . 
                                                                        $reg[$i] ['threshold_qty'] . ", " . 
                                                                        0 . ", " . 
                                                                        $reg[$i] ['result_type'] ; 
                                        $cont = $cont + 1;    
        
                                        $DatosMultisam2 =  $reg[$i]['till_no'][0] . ", " .
                                                            $reg[$i] ['invoice_no'][0] . ", " . 
                                                            $reg[$i] ['invoice_type'][0] . ", " . 
                                                            $reg[$i] ['paym_cd'] . ", " . 
                                                            $reg[$i] ['seq_no'] . ", " . 
                                                        "'".  $reg[$i] ['id'] . "', " . 
                                            "to_date('" . $reg[$i] ['paym_date'] . "', 'YYYYMMDD') " . ", " . 
                                            quitarDecimales($reg[$i] ['paym_amount']); 
                                        } catch (\Throwable $th) {
                                            echo('<br>');
                                            echo('<br>');
                                            print_r('Error al procesar  Multisam en el archivo ' . $to);
                                            echo('<br>');
                                            echo('<br>');
                                        }
                                    }
                                }  
                                
                            }
                        } 
                    }   
                    else{ 
                        try{
                            foreach ($reg as $id){ 
                                ${"DatosMultisam1_" . $cont} = $id ['till_no'] . ", " . 
                                                            $id ['invoice_no'] . ", " . 
                                                            $id ['invoice_type'] . ", " . 
                                                            $id ['mmail_no'] . ", " . 
                                                            $id ['maction_no'] . ", " . 
                                                            $id ['maction_type'] . ", " . 
                                                            $id ['version_no'] . ", " . 
                                                            $id ['threshold_qty'] . ", " . 
                                                            0 . ", " . 
                                                            $id ['result_type'];
                                $cont = $cont + 1;  
                            }   
                        }
                        catch(\Exception $e){   
                            for ($i=0; $i < $tam ; $i++) {  
                                if($tam ==  $i+1){    
                                    try {
                                        ${"DatosMultisam1_" . $cont} =  $reg[$i]['till_no'][0] . ", " .
                                                                    $reg[$i] ['invoice_no'][0] . ", " . 
                                                                    $reg[$i] ['invoice_type'][0] . ", " . 
                                                                    $reg[$i] ['mmail_no'] . ", " . 
                                                                    $reg[$i] ['maction_no'] . ", " . 
                                                                    $reg[$i] ['maction_type'] . ", " . 
                                                                    $reg[$i] ['version_no'] . ", " . 
                                                                    $reg[$i] ['threshold_qty'] . ", " . 
                                                                    0 . ", " . 
                                                                    $reg[$i] ['result_type'] ; 
                                    $cont = $cont + 1;    

                                    $DatosMultisam2 =  $reg[$i]['till_no'][0] . ", " .
                                                        $reg[$i] ['invoice_no'][0] . ", " . 
                                                        $reg[$i] ['invoice_type'][0] . ", " . 
                                                        $reg[$i] ['paym_cd'] . ", " . 
                                                        $reg[$i] ['seq_no'] . ", " . 
                                                    "'".  $reg[$i] ['id'] . "', " . 
                                        "to_date('" . $reg[$i] ['paym_date'] . "', 'YYYYMMDD') " . ", " . 
                                        quitarDecimales($reg[$i] ['paym_amount']); 
                                    } catch (\Throwable $th) {
                                        echo('<br>');
                                        echo('<br>');
                                        print_r('Error al procesar  Multisam en el archivo ' . $to);
                                        echo('<br>');
                                        echo('<br>');
                                    }
                                }
                            }                       
                        }
                    }         
                } 
            } 
        } 
    

        if($unico)
        { 
            $InsertMultisam = $qryMultisam1 . $DatosMultisam1 . ")";  
            $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertMultisam);  
        }   
        else{
            for ($i=0; $i < $cont; $i++) {      
                ${"InsertMultisam" . $i} = $qryMultisam1 . ${"DatosMultisam1_" . $i} . ")";   
            } 
            for ($i=0; $i < $cont; $i++) { 
                $datos = DB::connection(Store::getStoreDatabase(6))->insert(${"InsertMultisam" . $i}); 
            }  

            if(isset($DatosMultisam2))
            {
                $InsertMultisam2 = $qryMultisam2 . $DatosMultisam2 . ")";  
                $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertMultisam2);  
            }
        }  
        return "Finalizo correctamente MULTISAM";
        
    } catch (\Throwable $th) {
        return "Error al procesar MULTISAM";
    }

}

function vat(string $to){

    try{
    $xmlString = file_get_contents($to);
    $xmlObject = simplexml_load_string($xmlString);      
 
    $json = json_encode($xmlObject);
    $array = json_decode($json, true);     
    
    $qryVat = "insert into invoice_vat (till_no, invoice_no, invoice_type, vat_no, 
                    vat_amount, basic_amount, LEGAL_BASE, LEGAL_EXCL) values (";
    $cont = 0;
    $unico = false;

    foreach ($array as $key => $valor){
        if($key == "vats"){          
            foreach ($valor as $reg){    
                $tag = (array)$reg;
                if(is_array($tag) && count($tag)==7){    
                    $unico = true;    
                    try {    
                        $DatosVat = $tag ["till_no"] . ", " .
                                    $tag ["invoice_no"] . ", " .
                                    $tag ["invoice_type"] .   ", " .
                                    $tag ["vat_no"] .   ", " .
                                    quitarDecimales($tag ["vat_amount"]) .   ", " .
                                    quitarDecimales($tag ["basic_amount"])  .   ", " .
                                    0  .   ", " .
                                    0;    
                        
                    }
                    catch(\Exception $e){ 
                        $unico = false;  
                        foreach ($reg as $id){ 
                            ${"DatosVat" . $cont} = $id ['till_no'] . ", " .
                                                        $id ['invoice_no'] . ", " .
                                                        $id ['invoice_type'] .   ", " .
                                                        $id ['vat_no'] .   ", " .
                                                        quitarDecimales($id ['vat_amount']) .   ", " .
                                                        quitarDecimales($id ['basic_amount']) .   ", " .
                                                        0 .   ", " .
                                                        0;         
                            $cont = $cont + 1;   
                    
                        }
                    }
                }   
                else{ 
                    foreach ($reg as $id){ 
                        ${"DatosVat" . $cont} = $id ['till_no'] . ", " .
                                                    $id ['invoice_no'] . ", " .
                                                    $id ['invoice_type'] .   ", " .
                                                    $id ['vat_no'] .   ", " .
                                                    quitarDecimales($id ['vat_amount']) .   ", " .
                                                    quitarDecimales($id ['basic_amount']) .   ", " .
                                                    0 .   ", " .
                                                    0;        
                        $cont = $cont + 1;   
                    }   
                }         
            } 
        }         
    }   
 
    if($unico)
    { 
        $InsertVat = $qryVat . $DatosVat . ")"; 
        $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertVat); 
    }   
    else{
        for ($i=0; $i < $cont; $i++) {      
            ${"InsertVat" . $i} = $qryVat . ${"DatosVat" . $i} . ")"; 
        }
        for ($i=0; $i < $cont; $i++) { 
            $datos = DB::connection(Store::getStoreDatabase(6))->insert(${"InsertVat" . $i}); 
        }  
    }
    return "Finalizo correctamente VAT";
    
    } catch (\Throwable $th) {
        return "Error al procesar VAT";
    }
}

function tax(string $to){  

   try{
   $xmlString = file_get_contents($to);
   $xmlObject = simplexml_load_string($xmlString);      
 
    $json = json_encode($xmlObject);
    $array = json_decode($json, true);    

    $qrTax = "insert into invoice_tax (invoice_type, invoice_no, till_no, tax_id, tax_amount) values (";
    $cont = 0;
    $unico = false;  

    foreach ($array as $key => $valor){
        if($key == "taxs"){          
            foreach ($valor as $reg){    
                $tag = (array)$reg; 
                if(is_array($tag) && count($tag)==7){    
                    $unico = true;   
                    try {    
                        $DatosTax = $tag ["invoice_type"] . ", " .
                                    $tag ["invoice_no"] . ", " .
                                    $tag ["till_no"] .   ", " .
                                    $tag ["tax_id"] .   ", " .
                                    quitarDecimales($tag ["tax_amount"])   ;    
                        
    
                    }
                    catch(\Exception $e){ 
                        $unico = false;  
                        foreach ($reg as $id){ 
                            ${"DatosTax" . $cont} = $id ['invoice_type'] . ", " .
                                                        $id ['invoice_no'] . ", " .
                                                        $id ['till_no'] .   ", " .
                                                        $id ['tax_id'] .   ", " .
                                                        quitarDecimales($id ['tax_amount'])   ;      
                            $cont = $cont + 1;   
                    
                        }
                    }
                }   
                else{      
                    foreach ($reg as $id){  
                        ${"DatosTax" . $cont} = $id ['invoice_type'] . ", " .
                                                $id ['invoice_no'] . ", " .
                                                $id ['till_no'] .   ", " .
                                                $id ['tax_id'] .   ", "  .
                                                quitarDecimales($id ['tax_amount'])
                                                ;         
                        $cont = $cont + 1;   
                    } 
                }       
            } 
        }         
    }       

    if($unico)
    {  
          $InsertTax = $qrTax . $DatosTax . ")";     
          $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertTax);
    }   
    else{  
        for ($i=0; $i < $cont; $i++) {     
            ${"InsertTax" . $i} = $qrTax . ${"DatosTax" . $i} . ")";   
        }
        for ($i=0; $i < $cont; $i++) { 
          $datos = DB::connection(Store::getStoreDatabase(6))->insert(${"InsertTax" . $i}); 
        }  
    }   
    return "Finalizo correctamente TAX";
    
    } catch (\Throwable $th) {
        return "Error al procesar TAX";
    }
}

function QuitarDecimales(string $cadena){    
    for ($i=0; $i < strlen($cadena); $i++) {
        if ($cadena[$i] == "."){
            $decimal = substr($cadena, -(strlen($cadena)-$i), 3);   
            $entero = substr($cadena, -strlen($cadena), $i);    
            return $entero . $decimal;            
        }
    } 
}
 
function Nulos($valor) {

    if($valor == null)
    {
        return 0;
    }else{        
        return $valor;
    }
}

function multisam1X2(string $to){ 

    try{
        $xmlString = file_get_contents($to);
        $xmlObject = simplexml_load_string($xmlString);      
     
        $json = json_encode($xmlObject);
        $array = json_decode($json, true);     
        
        $qryMultisam1 = "insert into invoice_multisam (till_no, invoice_no, invoice_type, mmail_no, 
                        maction_no, makro_action_type, version_no, threshold_qty, result, result_type) values ("; 
    
        foreach ($array as $key => $valor){
            if($key == "multisams"){    
                foreach ($valor as $reg){       
                    $tag = (array)$reg;   
                       $DatosMultisam1 = $tag ['till_no'][0] . ", " . 
                        $tag ['invoice_no'][0] . ", " . 
                        $tag ['invoice_type'][0] . ", " . 
                        $tag ['mmail_no'] . ", " . 
                        $tag ['maction_no'] . ", " . 
                        $tag ['maction_type'] . ", " . 
                        $tag ['version_no'] . ", " . 
                        $tag ['threshold_qty'] . ", " . 
                        0 . ", " . 
                        $tag ['result_type'];       
                }
            }
        }
 
        $InsertMultisam = $qryMultisam1 . $DatosMultisam1 . ")";   
        $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertMultisam);      
        return "Finalizo correctamente MULTISAM/ITEM1X2";
    }
    catch(\Exception $e){        
        return "Error al procesar MULTISAM/ITEM1X2";
    }
}

function vat1X2(string $to){
    try{
        $xmlString = file_get_contents($to);
        $xmlObject = simplexml_load_string($xmlString);      
     
        $json = json_encode($xmlObject);
        $array = json_decode($json, true);     
        
        $qryVat = "insert into invoice_vat (till_no, invoice_no, invoice_type, vat_no, 
                        vat_amount, basic_amount, LEGAL_BASE, LEGAL_EXCL) values ("; 
    
        foreach ($array as $key => $valor){
            if($key == "vats"){          
                foreach ($valor as $reg){    
                    $tag = (array)$reg; 
                    $DatosVat = $tag ["till_no"][0] . ", " .
                                $tag ["invoice_no"][0] . ", " .
                                $tag ["invoice_type"][0] .   ", " .
                                $tag ["vat_no"] .   ", " .
                                quitarDecimales($tag ["vat_amount"]) .   ", " .
                                quitarDecimales($tag ["basic_amount"])  .   ", " .
                                0  .   ", " .
                                0;    
                }
            }
        }
        $InsertVat = $qryVat . $DatosVat . ")"; 
        $datos = DB::connection(Store::getStoreDatabase(6))->insert($InsertVat); 
        return "Finalizo correctamente VAT1X2";
    }
    catch (\Exception $e){
        return "Finalizo correctamente VAT1X2";
    }
}


