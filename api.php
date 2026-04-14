<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require "db.php";

/* ================= FORMAT ================= */

$contentType = $_SERVER["CONTENT_TYPE"] ?? "";
$responseFormat = str_contains($contentType, "xml") ? "xml" : "json";

/* ================= INPUT ================= */

function getInput($format){
    $raw = file_get_contents("php://input");

    if($format === "json"){
        return json_decode($raw, true);
    } else {
        $xml = simplexml_load_string($raw);
        return json_decode(json_encode($xml), true);
    }
}

$method = $_SERVER["REQUEST_METHOD"];
$id = $_GET["id"] ?? null;

/* ================= RESPONSE HELPERS ================= */

function sendJSON($data, $code = 200){
    http_response_code($code);
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
}

function sendXML($data, $root="response", $code=200){
    http_response_code($code);
    header("Content-Type: application/xml");

    $xml = new SimpleXMLElement("<$root/>");

    $add = function($value, $key) use (&$add, &$xml){
        if(is_array($value)){
            $sub = $xml->addChild($key);
            foreach($value as $k=>$v){
                if(is_numeric($k)) $k = "item";
                $child = $sub->addChild($k);
                if(is_array($v)){
                    foreach($v as $kk=>$vv){
                        $child->addChild($kk, htmlspecialchars($vv));
                    }
                } else {
                    $child[0] = htmlspecialchars($v);
                }
            }
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    };

    foreach($data as $k=>$v){
        $add($v,$k);
    }

    echo $xml->asXML();
    exit;
}

/* ================= ROUTER ================= */

switch($method){

// ===== GET =====
case "GET":

    if ($id){
        $stmt = $pdo->prepare("SELECT * FROM records WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item){
            $responseFormat === "json"
                ? sendJSON(["message"=>"Record non trovato"],404)
                : sendXML(["message"=>"Record non trovato"],"response",404);
        }

        $responseFormat === "json"
            ? sendJSON($item)
            : sendXML($item,"record");

    } else {
        $stmt = $pdo->query("SELECT * FROM records");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $responseFormat === "json"
            ? sendJSON($data)
            : sendXML(["record"=>$data],"records");
    }

break;


// ===== POST =====
case "POST":

    $input = getInput($responseFormat);

    $stmt = $pdo->prepare("INSERT INTO records (nome, valore) VALUES (?, ?)");
    $stmt->execute([
        $input["nome"] ?? "",
        $input["valore"] ?? 0
    ]);

    $newRecord = [
        "id"=>$pdo->lastInsertId(),
        "nome"=>$input["nome"],
        "valore"=>$input["valore"]
    ];

    $responseFormat === "json"
        ? sendJSON($newRecord,201)
        : sendXML($newRecord,"record",201);

break;


// ===== PUT =====
case "PUT":

    if (!$id){
        $responseFormat === "json"
            ? sendJSON(["message"=>"ID richiesto"],400)
            : sendXML(["message"=>"ID richiesto"],"response",400);
    }

    $input = getInput($responseFormat);

    $stmt = $pdo->prepare("UPDATE records SET nome=?, valore=? WHERE id=?");
    $stmt->execute([
        $input["nome"],
        $input["valore"],
        $id
    ]);

    if ($stmt->rowCount() == 0){
        $responseFormat === "json"
            ? sendJSON(["message"=>"Record non trovato"],404)
            : sendXML(["message"=>"Record non trovato"],"response",404);
    }

    $updated = [
        "id"=>$id,
        "nome"=>$input["nome"],
        "valore"=>$input["valore"]
    ];

    $responseFormat === "json"
        ? sendJSON($updated)
        : sendXML($updated,"record");

break;


// ===== DELETE =====
case "DELETE":

    if (!$id){
        $responseFormat === "json"
            ? sendJSON(["message"=>"ID richiesto"],400)
            : sendXML(["message"=>"ID richiesto"],"response",400);
    }

    $stmt = $pdo->prepare("DELETE FROM records WHERE id=?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() == 0){
        $responseFormat === "json"
            ? sendJSON(["message"=>"Record non trovato"],404)
            : sendXML(["message"=>"Record non trovato"],"response",404);
    }

    $responseFormat === "json"
        ? sendJSON(["message"=>"Eliminato"])
        : sendXML(["message"=>"Eliminato"]);

break;


// ===== DEFAULT =====
default:
    $responseFormat === "json"
        ? sendJSON(["message"=>"Metodo non consentito"],405)
        : sendXML(["message"=>"Metodo non consentito"],"response",405);
}