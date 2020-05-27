<?php

function api_empty_endpoint(&$responce)
{
    $responce["http_code"]     = 501;
    $responce["description"]   = "Please specify the requested endpoint";
}

function api_unsupported_endpoint($endpoint, &$responce)
{
    $responce["http_code"]     = 501;
    $responce["description"]   = "Endpoint `" . $endpoint . "` is not supported";
}

function api_handle_font($request, &$responce)
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    switch ($requestMethod) {
        case "GET":
            $fontId = $request[2];
        
            if ($fontId == "")
            {
                $responce["http_code"]     = 400;
                $responce["description"]   = "`fontId` is required";
                
                return false;
            }
        
            $font = databaseGetFont($fontId);
        
            if ($font === null)
            {
                $responce["http_code"]     = 404;
                $responce["description"]   = "The requested font was not found";
        
                return false;
            }

            if (   ($font["userId"] != getAuthenticatedUserId())
               &&  ($font["shared"] == false)   )
            {
                $responce["http_code"]     = 403;
                $responce["description"]   = "This font is private";
        
                return false;
            }
        
            return $font;
            break;

        case "POST":
            
            break;
        
        default:
            $responce["http_code"]     = 501;
            $responce["description"]   = "Method `" . $requestMethod . 
                "` is not supported by endpoint `" . $request[1] . "`";
            break;
    }
}

function api_handle_fonts($request, &$responce)
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if ($requestMethod == "GET")
    {
        $fonts = databaseGetFonts(getAuthenticatedUserId());
    
        if ($fonts === null)
        {
            $responce["http_code"]     = 404;
            $responce["description"]   = "User has no fonts created";
    
            return false;
        }
    
        return $fonts;
    }
    else
    {
        $responce["http_code"]     = 501;
        $responce["description"]   = "Method `" . $requestMethod . 
            "` is not supported by endpoint `" . $request[1] . "`";
    }
}

function api_handle_glyph($request, &$responce)
{
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if ($requestMethod == "GET")
    {
        $fontId = $request[2];
        $charCode = $request[3];
    
        if (($fontId == "") || ($charCode == ""))
        {
            $responce["http_code"]     = 400;
            $responce["description"]   = "Endpoint should be accessed as" .
                "/glyph/{fontId}/{charCode} (both parameters are required)";
            
            return false;
        }
    
        $glyph = databaseGetGlyph($fontId, $charCode);
    
        if ($glyph === null)
        {
            $responce["http_code"]     = 404;
            $responce["description"]   = "The requested glyph was not found";
    
            return false;
        }
    
        return json_decode($glyph, true);
    }
    else
    {
        $responce["http_code"]     = 501;
        $responce["description"]   = "Method `" . $requestMethod . 
            "` is not supported by endpoint `" . $request[1] . "`";
    }
}

?>