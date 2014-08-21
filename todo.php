<?php

// Create array to hold list of todo items
$items = array();

// List array items formatted for CLI
function list_items($list)
{
    // Return string of list items separated by newlines.
    // Should be listed [KEY] Value like this:
    // [1] TODO item 1
    // [2] TODO item 2 - blah
    // DO NOT USE ECHO, USE RETURN
    $list_to_print = "";
    if (empty($list)) {
            $list_to_print = "Empty TODO List" . PHP_EOL;
    } else {
        foreach ($list as $key => $item) {
            $list_to_print .= "[" . ++$key . "]\t$item" . PHP_EOL;
        }
    }
    return $list_to_print;
}

// Get STDIN, strip whitespace and newlines, 
// and convert to uppercase if $upper is true
function get_input($menu_input = FALSE) {
   return ($menu_input ? substr(strtoupper(trim(fgets(STDIN))), 0, 1) : trim(fgets(STDIN)));  
 }

function sort_menu($list){
    switch(get_input(TRUE)) {
        case 'A':
            asort($list);
            break;
        case 'Z':
            arsort($list);
            break;
        case 'O':
            ksort($list);
            break;
        case 'R':
            krsort($list);
            break;
    }
    return $list;
}

function add_file($filepath, $old_list) {
    $handle = fopen($filepath, 'r');
    $saved_list = trim(fread($handle, filesize($filepath))); 
    return array_merge($old_list, explode("\n", $saved_list)); // converts saved list to array and merges with old list
}

// The loop!
do {
    // Echo the list produced by the function
    echo list_items($items);

    // Show the menu options
    echo '(N)ew item, (O)pen, (S)ort, (R)emove item, (Q)uit : ';

    // Get the input from user
    // Use trim() to remove whitespace and newlines
    $input = get_input(TRUE);

    // Check for actionable input
    if ($input == 'N') {
        // Ask for entry
        echo 'Enter item: ';
        // Add entry to list array
        $new_item = get_input();
        echo "Place at (B)eginning or (E)nd of List? ";
        switch(get_input(TRUE)) {
            case 'B':
                array_unshift($items, $new_item);
                break;
            case 'E': 
                array_push($items, $new_item);
                break;
            default:
                array_push($items, $new_item);
                break;
        }

    } elseif ($input == 'R') {
         // Remove which item?
        echo 'Enter item number to remove: ';
        // Get array key
        $key = get_input();
        // Remove from array
        unset($items[--$key]);
    } elseif($input == 'S') {
        echo "(A)-Z, (Z)-A, (O)rder entered, (R)everse order entered" . PHP_EOL;
        $items = sort_menu($items);
    } elseif ($input == 'F') {
        array_shift($items);
    } elseif ($input == 'L') {
        array_pop($items);
    } elseif ($input == 'O') {
        $filepath = get_input();
        $items = add_file($filepath, $items);
    } else {
        echo 'Invalid input' . PHP_EOL;
    }
 // Exit when input is (Q)uit
} while ($input != 'Q');

 // Say Goodbye!
echo "Goodbye!" . PHP_EOL;

// Exit with 0 errors
exit(0);
