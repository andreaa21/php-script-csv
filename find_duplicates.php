<?php

// definisco la directory di partenza
$dir = "VIDEO/";


// prendo tutti i file dalla cartella e dalle sue sottocartelle
function get_files($dir)
{
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = array();
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

// salvo i files in una variabile
$all_files = get_files($dir);



// creo un array con i nomi dei file e i loro percorsi
$file_paths = array();
foreach ($all_files as $file_path) {
    $file_name = basename($file_path);
    $file_paths[$file_name][] = $file_path;
}


// filtro i duplicati
$duplicate_files = array_filter($file_paths, function ($paths) {
    return count($paths) > 1;
});


// creo un array con le informazioni dei duplicati
$duplicates_info = array();
foreach ($duplicate_files as $file_name => $file_paths) {
    $occurrences = count($file_paths);
    $paths = implode(";", $file_paths);
    $duplicates_info[] = array($file_name, $occurrences, $paths);
}


// scrivo le informazioni in un file csv
$file_csv = fopen('duplicati.csv', 'w');

foreach ($duplicates_info as $duplicate) {
    fputcsv($file_csv, $duplicate);
}

fclose($file_csv);
