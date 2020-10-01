<?php 
if (!function_exists('titleCase')) {
    function titleCase($string) 
    {
        $word_splitters = array(' ', '-', "O'", "L'", "D'", 'St.', 'Mc');
        $lowercase_exceptions = array('the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'of', 'and', "l'", "d'");
        $uppercase_exceptions = array('III', 'IV', 'VI', 'VII', 'VIII', 'IX');
    
        $string = mb_strtolower($string, 'UTF-8');
        foreach ($word_splitters as $delimiter)
        { 
            $words = explode($delimiter, $string); 
            $newwords = array(); 
            foreach ($words as $word)
            { 
                if (in_array(mb_strtoupper($word, 'UTF-8'), $uppercase_exceptions)) {
                   
                    $word = mb_strtoupper($word, 'UTF-8');
                } else if (!in_array($word, $lowercase_exceptions)) {
                    
                    $word = mb_convert_case($word, MB_CASE_TITLE, "UTF-8");
                }
                    
    
                $newwords[] = $word;
            }
    
            if (in_array(mb_strtolower($delimiter,'UTF-8'), $lowercase_exceptions))
                $delimiter = mb_strtolower($delimiter, 'UTF-8');
    
            $string = join($delimiter, $newwords); 
        } 
        return $string; 
    }
}
if (!function_exists('arraySearch')) {
    function arraySearch($array, $search_list) { 
  
        // Create the result array 
        $result = []; 
      
        // Iterate over each array element 
        foreach ($array as $key => $value) { 
      
            // Iterate over each search condition 
            foreach ($search_list as $k => $v) { 
          
                // If the array element does not meet 
                // the search condition then continue 
                // to the next element 
                if (!isset($value[$k]) || $value[$k] != $v) 
                { 
                      
                    // Skip two loops 
                    continue 2; 
                } 
            } 
          
            // Append array element's key to the 
            //result array 
            $result[] = $value; 
        } 
      
        // Return result  
        return $result; 
    } 
}
if (!function_exists('getEventInfos')) {
    function getEventInfos($eventSlug) { 
  
        $events = [
            'gatineau' => [
                'city' => 'Gatineau',
                'id' => 1
            ],
            'toronto' => [
                'city' => 'Toronto',
                'id' => 2
            ],
            'levis' => [
                'city' => 'Lévis',
                'id' => 3
            ],
            'flofest' => [
                'city' => 'FLOFEST',
                'id' => 4
            ],
            'saintehyacinthe' => [
                'city' => 'saintehyacinthe',
                'id' => 5
            ]
        ];
        return $events[$eventSlug];
    } 
}
