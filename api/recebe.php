<?php
require_once('../conn.php');
#########################################################################
#######  VARIAVEIS NECESSARIAS
$numero_get = $_GET['telefone'];
$usuario_get = $_GET['usuario'];
$msg_usuario = $_GET['msg'];
####################################################################
####################### FUNÇÕES
print_r($_REQUEST);

##### DATA E HORA

date_default_timezone_set('America/Sao_Paulo');
$now = time();

$data_hora = date('Y-m-d H:i:s', $now);
#echo $data_hora;

##### FUNÇÃO PRA IDENTIFICAR NUMEROS
function ehNumero($texto){
    return is_numeric($texto);
}
##############################################
########## FUNÇÃO LETRA MAIUSCULA

function primeiraLetraMaiuscula($texto) {
    $primeiraLetra = mb_strtoupper(mb_substr($texto, 0, 1));
    $restante = mb_strtolower(mb_substr($texto, 1));
    return $primeiraLetra . $restante;
}
###########################################################################################
#BUSCA CLIENTE
$busca_cliente = "SELECT * FROM cliente WHERE telefone = '$numero_get' AND email_painel = '$usuario_get'";
$cliente = mysqli_query($conn, $busca_cliente);
$total_cliente = mysqli_num_rows($cliente);

while($dados_cliente = mysqli_fetch_array($cliente)){
    $id_cliente = $dados_cliente['id'];
    $telefone_cliente = $dados_cliente['telefone'];
    $nome_cliente = $dados_cliente['nome'];
    $endereco_cliente = $dados_cliente['endereco'];    
    $email_painel_cliente = $dados_cliente['email_painel'];
    $situacao_cliente = $dados_cliente['situacao'];

}
##########################################################################################
#BUSCA LOGIN

$busca_painel = "SELECT * FROM login WHERE email = '$usuario_get' ";
$usuario_painel = mysqli_query($conn, $busca_painel);

while($dados_painel = mysqli_fetch_array($usuario_painel)){

$email_painel = $dados_painel['email'];
$senha_painel = $dados_painel['senha'];
$nome_painel = $dados_painel['nome'];
$dinheiro_painel = $dados_painel['dinheiro'];
$pix_painel = $dados_painel['pix'];
$cartao_painel = $dados_painel['cartao'];
$caderneta_painel = $dados_painel['caderneta'];
$status_painel = $dados_painel['status'];    
$prod_gas = $dados_painel['prod_gas'];
$prod_agua = $dados_painel['prod_agua'];

}
##########################################################################################
if($total_cliente == 0){
    $sql = "INSERT INTO cliente (telefone, email_painel )  VALUES ('$numero_get','$usuario_get')";
    $query = mysqli_query($conn,$sql);
    if($query){


        $msg= 'Para começar, me diga seu nome 😊';
        $sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg', '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);

    }

}
######################
## PREENCHENDO O NOME DO CLIENTE

if($total_cliente == 1 && $nome_cliente == NULL){

$nome_cliente = primeiraLetraMaiuscula($msg_usuario);    

$sql = "UPDATE cliente  SET nome = '$msg_usuario' WHERE email_painel='$usuario_get' AND telefone = '$numero_get'";
$query = mysqli_query($conn,$sql);

$msg = "Bem vindo *$msg_usuario* ao nosso de delivery de água e gás!👷🏼
Escolha a abaixo a opção desejada.
*(1)* Galão de água 💧 *R$ $prod_agua* 
*(2)* Botijão de gás 🔥 *R$ $prod_gas*

*digite apenas os números* 👍🏼";   

$sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);

}

############################################################################################################

if($total_cliente == '1' && $nome_cliente == TRUE && $situacao_cliente == NULL){

if(ehNumero($msg_usuario)){


if($msg_usuario == '1'){

$status = 'compra_agua'; 

$sql = "INSERT INTO pedidos (nome_cliente,id_cliente,telefone,endereco,status,data_hora,email_painel) VALUES ('$nome_cliente','$id_cliente','$telefone_cliente','$endereco_cliente','$status','$data_hora','$email_painel_cliente')";
$query = mysqli_query($conn,$sql);

if($query){

$msg = 'Quantos galões de *água* voce quer? 💧
Por exemplo digite *1*, *2* ou *3*, de acordo com quantidade. ';
$sql = "INSERT INTO envios (telefone,msg ,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);
if($query){

$sql = "UPDATE cliente  SET situacao = 'compra_agua' WHERE  email_painel='$usuario_get' AND telefone = '$numero_get'";
$query = mysqli_query($conn,$sql);

}

}
    
}

if($msg_usuario == '2'){

    $status = 'compra_gas'; 
    
    $sql = "INSERT INTO pedidos (nome_cliente,id_cliente,telefone,endereco,status,data_hora,email_painel) VALUES ('$nome_cliente','$id_cliente' , '$telefone_cliente' ,'$endereco_cliente','$status','$data_hora','$email_painel_cliente')";
    $query = mysqli_query($conn,$sql);
    
    if($query){

        $msg = 'Quantos botijões de *gás*  voce quer? 🔥
        Por exemplo digite *1*, *2* ou *3*, de acordo com quantidade. ';

        $sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);
if($query){

$sql = "UPDATE cliente  SET situacao = 'compra_gas' WHERE  email_painel='$usuario_get' AND telefone = '$numero_get'";
$query = mysqli_query($conn,$sql);

}

    }
        
    }
    
#####acrescento o pedido no carrinho

}####################################   if(ehNumero($msg_usuario)){

if(!ehNumero($msg_usuario)){

##### trazer o menu novamente
$msg = "Escolha a abaixo a opção desejada 
do nosso de delivery de água e gás!👷🏼.

*(1)* Galão de água 💧 *R$ $prod_agua* 
*(2)* Botijão de gás 🔥 *R$ $prod_gas*

*digite apenas os números* 👍🏼";   

$sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);

}

}#######################  if(!ehNumero($msg_usuario)){

#######################################################################################################
########## LOOP LOOP LOOP LOOP LOOP LOOP LOOP LOOP LOOP LOOP LOOP LOOP
#########################################################################

if($situacao_cliente == 'compra_agua' && $nome_cliente == TRUE){

    if(ehNumero($msg_usuario)){

$sql = "UPDATE pedidos SET quant_agua = '$msg_usuario' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status = 'compra_agua'";
$query = mysqli_query($conn,$sql);
if($query){

$busca_pedidos = "SELECT * FROM pedidos WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status = 'compra_agua' ";
$pedidos = mysqli_query($conn, $busca_pedidos );

while($dados_pedidos = mysqli_fetch_array($pedidos)){
    $id_dados_pedidos = $dados_pedidos['id'];
    $nome_cliente_dados_pedidos = $dados_pedidos['nome_cliente'];
    $telefone_cliente_dados_pedidos = $dados_pedidos['telefone'];
    $endereco_dados_pedidos = $dados_pedidos['endereco'];
    $quant_gas_dados_pedidos = $dados_pedidos['quant_gas'];
    $quant_agua_dados_pedidos = $dados_pedidos['quant_agua'];
    $forma_pagamento_dados_pedidos = $dados_pedidos['forma_pagamento'];
    $status_dados_pedidos = $dados_pedidos['status'];
    $data_hora_dados_pedidos = $dados_pedidos['data_hora'];
    $email_painel_dados_pedidos = $dados_pedidos['email_painel'];
}

$hora = date("H:i", strtotime($data_hora_dados_pedidos));
$data = date("d/m/Y", strtotime($data_hora_dados_pedidos));

if($quant_agua_dados_pedidos > 0 ){
$pedido1 = "*$quant_agua_dados_pedidos* -  Galão de Água💧";

}

if($quant_gas_dados_pedidos > 0 ){
    $pedido2 = "*$quant_gas_dados_pedidos* -  Botijão de Gás🔥";
    
    }
    
$total_agua = $prod_agua  * $quant_agua_dados_pedidos;
$total_gas = $prod_gas * $quant_gas_dados_pedidos ;
$total_carrinho = $total_agua + $total_gas;

$msg = "📋 NOTA FISCAL - PEDIDO DE ÁGUA COM GÁS 🍾
📅 Data: $data
⏰ Hora: $hora

🛒 Pedido:
$pedido1
$pedido2  

💰 Total: R$ $total_carrinho";

$sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);
if($query){

$sql = "UPDATE cliente SET situacao = 'inicio_compra' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get'";
$query = mysqli_query($conn,$sql);

$sql = "UPDATE pedidos SET status = 'inicio_compra' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status = 'compra_agua'";    
$query = mysqli_query($conn,$sql);

if($query){
    $msg = "Escolha a abaixo a opção desejada.
            
    *(1)* Galão de água 💧 *R$ $prod_agua*
    *(2)* Botijão de gás 🔥 *R$ $prod_gas*
    *(3)* Finalizar compra. 🛒
        
    digite apenas os números 👍🏼";
    
        $sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
        $query = mysqli_query($conn,$sql);
    
    }   

}

}##if($query){


}#if(ehNumero($msg_usuario)){

    if(!ehNumero($msg_usuario)){

$msg = "Quantos galões de *água* voce quer? 💧
Por exemplo digite *1*, *2* ou *3*, de acordo com quantidade.

*digite apenas os números* 👍🏼";
        
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
        $query = mysqli_query($conn,$sql);

    }

}#if($situacao_cliente == 'compra_agua' && $nome_cliente == TRUE){

########################################################################################################
##############  COMPRA GAS #####
##########################################################################################

if($situacao_cliente == 'compra_gas' && $nome_cliente == TRUE){

    if(ehNumero($msg_usuario)){

$sql = "UPDATE pedidos SET quant_gas = '$msg_usuario' WHERE   telefone = '$numero_get' AND email_painel = '$usuario_get' AND status = 'compra_gas'";
$query = mysqli_query($conn,$sql);
if($query){

$busca_pedidos = "SELECT * FROM pedidos WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status = 'compra_gas' ";
$pedidos = mysqli_query($conn, $busca_pedidos );

while($dados_pedidos = mysqli_fetch_array($pedidos)){
    $id_dados_pedidos = $dados_pedidos['id'];
    $nome_cliente_dados_pedidos = $dados_pedidos['nome_cliente'];
    $telefone_cliente_dados_pedidos = $dados_pedidos['telefone'];
    $endereco_dados_pedidos = $dados_pedidos['endereco'];
    $quant_gas_dados_pedidos = $dados_pedidos['quant_gas'];
    $quant_agua_dados_pedidos = $dados_pedidos['quant_agua'];
    $forma_pagamento_dados_pedidos = $dados_pedidos['forma_pagamento'];
    $status_dados_pedidos = $dados_pedidos['status'];
    $data_hora_dados_pedidos = $dados_pedidos['data_hora'];
    $email_painel_dados_pedidos = $dados_pedidos['email_painel'];
}

$hora = date("H:i", strtotime($data_hora_dados_pedidos));
$data = date("d/m/Y", strtotime($data_hora_dados_pedidos));

if($quant_agua_dados_pedidos > 0 ){
$pedido1 = "*$quant_agua_dados_pedidos* -  Galão de Água💧";

}

if($quant_gas_dados_pedidos > 0 ){
    $pedido2 = "*$quant_gas_dados_pedidos* -  Botijão de Gás🔥";
    
    }
    
$total_agua = $prod_agua  * $quant_agua_dados_pedidos;
$total_gas = $prod_gas * $quant_gas_dados_pedidos ;
$total_carrinho = $total_agua + $total_gas;

$msg = "📋 NOTA FISCAL - PEDIDO DE ÁGUA E GÁS 🍾
📅 Data: $data
⏰ Hora: $hora

🛒 Pedido:
$pedido1
$pedido2  

💰 Total: R$ $total_carrinho";

$sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);
if($query){

$sql = "UPDATE cliente SET situacao = 'inicio_compra' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";
$query = mysqli_query($conn,$sql);

$sql = "UPDATE pedidos SET status = 'inicio_compra' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status = 'compra_gas' ";    
$query = mysqli_query($conn,$sql);

if($query){
$msg = "Escolha a abaixo a opção desejada.
        
*(1)* Galão de água 💧 *R$ $prod_agua*
*(2)* Botijão de gás 🔥 *R$ $prod_gas*
*(3)* Finalizar compra. 🛒
    
digite apenas os números 👍🏼";

    $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
    $query = mysqli_query($conn,$sql);

}        

}

}##if($query){

}#if(ehNumero($msg_usuario)){

    if(!ehNumero($msg_usuario)){

$msg = "Quantos botijões de *gás*  voce quer? 🔥
Por exemplo digite *1*, *2* ou *3*, de acordo com quantidade.

*digite apenas os números* 👍🏼";
        
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
        $query = mysqli_query($conn,$sql);

    }

}#if($situacao_cliente == 'compra_gas' && $nome_cliente == TRUE){

if($situacao_cliente == 'inicio_compra' && $nome_cliente == TRUE){    

if(ehNumero($msg_usuario)) {

if($msg_usuario == '1'){
$status = 'compra_agua';

$sql = "UPDATE cliente SET situacao = 'compra_agua' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get'";    
$query = mysqli_query($conn,$sql);


$sql = "UPDATE pedidos SET status = 'compra_agua' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='inicio_compra'";    
$query = mysqli_query($conn,$sql);
if($query){

$msg = 'Quantos galões de *água* voce quer? 💧
Por exemplo digite *1*, *2* ou *3*, de acordo com quantidade. ';

$sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
$query = mysqli_query($conn,$sql);   
}

}
if($msg_usuario == '2'){

    $status = 'compra_gas';

    $sql = "UPDATE cliente SET situacao = 'compra_gas' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
    $query = mysqli_query($conn,$sql);
    
    
    $sql = "UPDATE pedidos SET status = 'compra_gas' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='inicio_compra'";    
    $query = mysqli_query($conn,$sql);
    if($query){
    
    $msg = 'Quantos botijões de *gás*  voce quer? 🔥
Por exemplo digite *1*, *2* ou *3*, de acordo com quantidade. ';
    
    $sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
    $query = mysqli_query($conn,$sql);   
    }   

}

if($msg_usuario == '3'){

    $sql = "UPDATE cliente SET situacao = 'forma_pagamento' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get'";    
    $query = mysqli_query($conn,$sql);
    
    
    $sql = "UPDATE pedidos SET status = 'forma_pagamento' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='inicio_compra'";    
    $query = mysqli_query($conn,$sql);

    if($query){

if($dinheiro_painel > 0){
    $dimdim = "*(1)* Dinheiro 💵";
}
if($pix_painel > 0){
    $pix =  "*(2)* PIX 💳";
 }
 if($cartao_painel  > 0){
     $cartao_painel =  "*(3)* Cartão 💳"; 
 }
 if($caderneta_painel  > 0){
     $caderneta_painel = "*(4)* Caderneta 📖"; 
 }

$msg = "Escolha a forma de pagamento que voce deseja usar💰:
$dimdim
$pix
$cartao_painel
$caderneta_painel";

$sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);

    }

}

}#if(ehNumero($msg_usuario)) {
    
##################################################################################################

if(!ehNumero($msg_usuario)) {
$msg = "Escolha a abaixo a opção desejada.
        
*(1)* Galão de água 💧 *R$ $prod_agua*
*(2)* Botijão de gás 🔥 *R$ $prod_gas*
*(3)* Finalizar compra. 🛒
    
    digite apenas os números 👍🏼";

$sql = "INSERT INTO envios (telefone,msg,status,usuario) VALUES ('$numero_get','$msg','1','$usuario_get')";
$query = mysqli_query($conn,$sql);

}#if(!ehNumero($msg_usuario)) {

}#if($situacao_cliente == 'inicio_compra' && $nome_cliente == TRUE){    

###########################################################################################################
###########################################################################################################
 
    if($situacao_cliente == 'forma_pagamento' && $nome_cliente == TRUE){      


        if(ehNumero($msg_usuario)){
        
            if($msg_usuario == '1'){
        
                $sql = "UPDATE pedidos SET forma_pagamento = 'Dinheiro' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
                $query = mysqli_query($conn,$sql);
                if($query){
        
                  if($endereco_cliente == NULL){
        $msg = "🚚 Por favor qual o endereço da entrega."; 
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        
        
        $sql = "UPDATE pedidos SET status = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
        
        $sql = "UPDATE cliente SET situacao = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        
        
        }#if($endereco_cliente == NULL){ 
            
        if($endereco_cliente == TRUE){    
        
        $msg = "O endereço da entrega é esse?
        
        🚚 Entrega: *$endereco_cliente*
        
        *(1)* SIM
        *(2)* NÃO"; 
        
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        if($query){
        
        $sql = "UPDATE pedidos SET status = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
            
        $sql = "UPDATE cliente SET situacao = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
            
        
        }
        
        
        }#if($endereco_cliente == TRUE){    
        
        }#if($query){
        
        
        
        
        
        
        }####################################################
        
        
        if($msg_usuario == '2'){
        
            $sql = "UPDATE pedidos SET forma_pagamento = 'PIX' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
            $query = mysqli_query($conn,$sql);
            if($query){
        
              if($endereco_cliente == NULL){
        $msg = "🚚 Por favor qual o endereço da entrega."; 
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        
        
        $sql = "UPDATE pedidos SET status = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
        
        $sql = "UPDATE cliente SET situacao = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        
        
        }#if($endereco_cliente == NULL){ 
        
        if($endereco_cliente == TRUE){    
        
        $msg = "O endereço da entrega é esse?
        
        🚚 Entrega: *$endereco_cliente*
        
        *(1)* SIM
        *(2)* NÃO"; 
        
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        if($query){
        
        $sql = "UPDATE pedidos SET status = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
        
        $sql = "UPDATE cliente SET situacao = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        
        
        }
        
        
        }#if($endereco_cliente == TRUE){    
        
        }#if($query){
        
        }########################################################################
        
        
        if($msg_usuario == '3'){
        
            $sql = "UPDATE pedidos SET forma_pagamento = 'Cartão' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
            $query = mysqli_query($conn,$sql);
            if($query){
        
              if($endereco_cliente == NULL){
        $msg = "🚚 Por favor qual o endereço da entrega."; 
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        
        
        $sql = "UPDATE pedidos SET status = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
        
        $sql = "UPDATE cliente SET situacao = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        
        
        }#if($endereco_cliente == NULL){ 
        
        if($endereco_cliente == TRUE){    
        
        $msg = "O endereço da entrega é esse?
        
        🚚 Entrega: *$endereco_cliente*
        
        *(1)* SIM
        *(2)* NÃO"; 
        
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        if($query){
        
        $sql = "UPDATE pedidos SET status = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
        
        $sql = "UPDATE cliente SET situacao = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        
        
        }
        
        
        }#if($endereco_cliente == TRUE){    
        
        }#if($query){
        
        
        }######################################################################
        
        if($msg_usuario == '4'){
        
            $sql = "UPDATE pedidos SET forma_pagamento = 'Caderneta' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
            $query = mysqli_query($conn,$sql);
            if($query){
        
              if($endereco_cliente == NULL){
        $msg = "🚚 Por favor qual o endereço da entrega."; 
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        
        
        $sql = "UPDATE pedidos SET status = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
        
        $sql = "UPDATE cliente SET situacao = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        
        
        }#if($endereco_cliente == NULL){ 
        
        if($endereco_cliente == TRUE){    
        
        $msg = "O endereço da entrega é esse?
        
        🚚 Entrega: *$endereco_cliente*
        
        *(1)* SIM
        *(2)* NÃO"; 
        
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
        if($query){
        
        $sql = "UPDATE pedidos SET status = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='forma_pagamento'";    
        $query = mysqli_query($conn,$sql);
        
        $sql = "UPDATE cliente SET situacao = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        
        
        }
        
        
        }#if($endereco_cliente == TRUE){    
        
        }#if($query){
        
        
        
    
        }########################################################################
        
        
        
        
        
        }#if(ehNumero($msg_usuario)){
        
        
        ###########################################################################################
        if(!ehNumero($msg_usuario)){
        
            if($dinheiro_painel > 0){
                $dimdim = "*(1)* Dinheiro 💵";
            }
            if($pix_painel > 0){
                $pix =  '*(2)* PIX 💳';
             }
             if($cartao_painel  > 0){
                 $cartao_painel =  '*(3)* Cartão 💳'; 
             }
             if($caderneta_painel  > 0){
                 $caderneta_painel = '*(4)* Caderneta 📖'; 
             }
            
            $msg = "Escolha a forma de pagamento que voce deseja usar💰:
            $dimdim
            $pix
            $cartao_painel
            $caderneta_painel";
            
            $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
            $query = mysqli_query($conn,$sql);
            
        
        }#if(!ehNumero($msg_usuario)){
        
        
        }#if($situacao_cliente == 'forma_pagamento' && $nome_cliente == TRUE){   
        
        
########################################################################################################################################
########################################################################################################################################


if($situacao_cliente == 'cadastra_endereco' && $nome_cliente == TRUE){    

    $sql = "UPDATE cliente SET endereco = '$msg_usuario' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
    $query = mysqli_query($conn,$sql);
    
    if($query){
    
    $msg = "🚚 O Endereço abaixo está correto?
    
    $msg_usuario
    
    *(1)* SIM
    *(2)* NÃO
    ";
    $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
    $query = mysqli_query($conn,$sql);
    if($query){
    
    
        $sql = "UPDATE cliente SET situacao = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
        if($query){
        $sql = "UPDATE pedidos SET status = 'confirma_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='cadastra_endereco'";    
        $query = mysqli_query($conn,$sql);
        
    
    
        }
    
    
    }
    
    
    
    
    
    
    
    }#if($query){
    
    
    
    }#if($situacao_cliente == 'cadastrar_endereco' && $nome_cliente == TRUE){    
    
    
    if($situacao_cliente == 'confirma_endereco' && $nome_cliente == TRUE){
    
    
        if(ehNumero($msg_usuario)){
    
            if($msg_usuario == '1'){
    
    
                $busca_pedidos = "SELECT * FROM pedidos WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status = 'confirma_endereco' ";
                $pedidos = mysqli_query($conn, $busca_pedidos );
                
                while($dados_pedidos = mysqli_fetch_array($pedidos)){
                    $id_dados_pedidos = $dados_pedidos['id'];
                    $nome_cliente_dados_pedidos = $dados_pedidos['nome_cliente'];
                    $telefone_cliente_dados_pedidos = $dados_pedidos['telefone'];
                    $endereco_dados_pedidos = $dados_pedidos['endereco'];
                    $quant_gas_dados_pedidos = $dados_pedidos['quant_gas'];
                    $quant_agua_dados_pedidos = $dados_pedidos['quant_agua'];
                    $forma_pagamento_dados_pedidos = $dados_pedidos['forma_pagamento'];
                    $status_dados_pedidos = $dados_pedidos['status'];
                    $data_hora_dados_pedidos = $dados_pedidos['data_hora'];
                    $email_painel_dados_pedidos = $dados_pedidos['email_painel'];
                }
    
    
                $hora = date("H:i", strtotime($data_hora_dados_pedidos));
                $data = date("d/m/Y", strtotime($data_hora_dados_pedidos));
                if($quant_agua_dados_pedidos > 0){
                $pedido1 = "*$quant_agua_dados_pedidos* -  Galão de Água💧";
                }
                if($quant_gas_dados_pedidos > 0){
                $pedido2 = "*$quant_gas_dados_pedidos* -  Botijão de Gás🔥";
                }
                
                $total_agua = $prod_agua * $quant_agua_dados_pedidos;
                $total_gas = $prod_gas * $quant_gas_dados_pedidos ;
                $total_carrinho = $total_agua + $total_gas;
    
       $msg = "📋 NOTA FISCAL - PEDIDO ÁGUA E GÁS         
    
    📅 Data: $data
    ⏰ Hora: $hora 
    
    
    🛒 Pedido:
    $pedido1
    $pedido2 
    
    💰Forma de Pagamento:$forma_pagamento_dados_pedidos 
    
    🚚 Endereço:
    $endereco_cliente 
    
    💰 Total: R$ $total_carrinho";
       
    $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
    $query = mysqli_query($conn,$sql);
    
    if($query){
    
        $sql = "UPDATE cliente SET situacao = 'aguardando' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
        $query = mysqli_query($conn,$sql);
    
        if($query){
    
            $sql = "UPDATE pedidos SET status = 'aguardando' , endereco='$endereco_cliente' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='confirma_endereco'";    
            $query = mysqli_query($conn,$sql);
    
    
    
            $msg = "Seu pedido esta sendo processado👍🏼"; 
        
            $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
            $query = mysqli_query($conn,$sql);
    
    
        }
    
    
    
    }
    
    
    
    
    
            }#if($msg_usuario == '1'){
    ##########################################################################
    
    if($msg_usuario == '2'){
    
        $msg = "🚚 Por favor qual o endereço da entrega."; 
        
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
    
        if($query){
    
            $sql = "UPDATE pedidos SET status = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' AND status='confirma_endereco'";    
            $query = mysqli_query($conn,$sql);
        
            $sql = "UPDATE cliente SET situacao = 'cadastra_endereco' WHERE telefone = '$numero_get' AND email_painel = '$usuario_get' ";    
            $query = mysqli_query($conn,$sql);
        
    
    
    
        }
    
    
                
    
    }#if($msg_usuario == '2'){
    
    
    
        }#if(ehNumero($msg_usuario)){
    
    
    
    
    
            if(!ehNumero($msg_usuario)){
    
    $msg = "🚚 O Endereço abaixo está correto?
    
     $endereco_cliente
                
    *(1)* SIM
    *(2)* NÃO
    
    *digite apenas números.* ";
    
    
                $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
                $query = mysqli_query($conn,$sql);
    
    
    
    
    
            }#if(!ehNumero($msg_usuario)
    
    }
    
    
    
    if($situacao_cliente == 'aguardando' && $nome_cliente == TRUE){
    
        $msg = "Seu pedido esta sendo processado 👍🏼";
                
        $sql = "INSERT INTO envios (telefone, msg , status,usuario) VALUES ('$numero_get','$msg' , '1' ,'$usuario_get')";
        $query = mysqli_query($conn,$sql);
    }

        