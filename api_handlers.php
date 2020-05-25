<?php

function api_empty_endpoint(&$responce)
{
    $responce["ok"]            = false;
    $responce["error_code"]    = API_ERROR_EMPTY_QUERY;
    $responce["http_code"]     = 501;
    $responce["description"]   = "Please specify the requested endpoint";
}

function api_unsupported_endpoint($endpoint, &$responce)
{
    $responce["ok"]            = false;
    $responce["error_code"]    = API_ERROR_ENDPOINT_UNSUPPORTED;
    $responce["http_code"]     = 501;
    $responce["description"]   = "Endpoint `" . $endpoint . "` is not supported";
}

function api_parse_font($request, &$responce, &$database)
{
    $request_method = $_SERVER['REQUEST_METHOD'];

    switch ($request_method) {
        case "GET":
            $font_id = $_GET["fontId"];
        
            if ($font_id == "")
            {
                $responce["ok"]            = false;
                $responce["error_code"]    = API_ERROR_TOO_FEW_ARGUMENTS;
                $responce["http_code"]     = 400;
                $responce["description"]   = "`fontId` is required";
                
                return false;
            }
        
            $font = $database->get("fonts", [
                "fontId", "width", "height", "name", "userId", "shared"
            ], [
                "fontId" => $font_id
            ]);
        
            if ($font === null)
            {
                $responce["ok"]            = false;
                $responce["error_code"]    = API_ERROR_NOT_FOUND;
                $responce["http_code"]     = 404;
                $responce["description"]   = "The requested font was not found";
        
                return false;
            }

            $font["height"] =  (int)$font["height"];
            $font["width"]  =  (int)$font["width"];
            $font["shared"] = (bool)$font["shared"];
        
            $responce["ok"] = true;
        
            return $font;
            break;
        
        default:
            $responce["ok"]            = false;
            $responce["error_code"]    = API_ERROR_METHOD_UNSUPPORTED;
            $responce["http_code"]     = 501;
            $responce["description"]   = "Method `" . $request_method . 
                "` is not supported by endpoint `" . $request[1] . "`";
            break;
    }
}

function api_parse_fonts($request, &$responce, &$database)
{
    $request_method = $_SERVER['REQUEST_METHOD'];

    if ($request_method == "GET")
    {
        $fonts = $database->select("fonts", [
            "fontId", "width", "height", "name", "userId", "shared"
        ]);
    
        if ($fonts === null)
        {
            $responce["ok"]            = false;
            $responce["error_code"]    = API_ERROR_NOT_FOUND;
            $responce["http_code"]     = 404;
            $responce["description"]   = "User has no fonts created";
    
            return false;
        }
    
        $responce["ok"] = true;
    
        return $fonts;
    }
    else
    {
        $responce["ok"]            = false;
        $responce["error_code"]    = API_ERROR_METHOD_UNSUPPORTED;
        $responce["http_code"]     = 501;
        $responce["description"]   = "Method `" . $request_method . 
            "` is not supported by endpoint `" . $request[1] . "`";
    }
}

function api_parse_glyph($request, &$responce, &$database)
{
    $request_method = $_SERVER['REQUEST_METHOD'];

    if ($request_method == "GET")
    {
        $font_id = $_GET["fontId"];
        $char_code = $_GET["charCode"];
    
        if (($font_id == "") || ($char_code == ""))
        {
            $responce["ok"]            = false;
            $responce["error_code"]    = API_ERROR_TOO_FEW_ARGUMENTS;
            $responce["http_code"]     = 400;
            $responce["description"]   = "Both `fontId` and `charCode` are required";
            
            return false;
        }
    
        $glyph = $database->get("glyphs", "data", [
            "font" => $font_id,
            "charCode" => $char_code
        ]);
    
        if ($glyph === null)
        {
            $responce["ok"]            = false;
            $responce["error_code"]    = API_ERROR_NOT_FOUND;
            $responce["http_code"]     = 404;
            $responce["description"]   = "The requested glyph was not found";
    
            return false;
        }
    
        $responce["ok"] = true;
    
        return json_decode($glyph, true);
    }
    else
    {
        $responce["ok"]            = false;
        $responce["error_code"]    = API_ERROR_METHOD_UNSUPPORTED;
        $responce["http_code"]     = 501;
        $responce["description"]   = "Method `" . $request_method . 
            "` is not supported by endpoint `" . $request[1] . "`";
    }
}

?>