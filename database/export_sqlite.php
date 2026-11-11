<?php

declare(strict_types=1);

$databasePath = __DIR__ . DIRECTORY_SEPARATOR . 'database.sqlite';
$outputPath = __DIR__ . DIRECTORY_SEPARATOR . 'export-postgres.sql';

if (! is_file($databasePath)) {
    fwrite(STDERR, "Database SQLite tidak ditemukan: {$databasePath}" . PHP_EOL);
    exit(1);
}

$db = new PDO('sqlite:' . $databasePath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$quoteIdentifier = static fn (string $value): string => '"' . str_replace('"', '""', $value) . '"';
$quoteValue = static function (mixed $value): string {
    if ($value === null) {
        return 'NULL';
    }

    if (is_int($value) || is_float($value)) {
        return (string) $value;
    }

    return "'" . str_replace("'", "''", (string) $value) . "'";
};

$tables = $db->query(
    "select name from sqlite_master where type = 'table' and name not like 'sqlite_%' order by name"
)->fetchAll(PDO::FETCH_COLUMN);

$output = [
    '-- Export SQLite QR Order untuk PostgreSQL/Neon',
    '-- Dibuat pada ' . date('c'),
    'BEGIN;',
    '',
];

foreach ($tables as $table) {
    $identifier = $quoteIdentifier((string) $table);
    $columns = $db->query('pragma table_info(' . $identifier . ')')->fetchAll(PDO::FETCH_ASSOC);

    if ($columns === []) {
        continue;
    }

    $columnNames = array_map(
        static fn (array $column): string => $quoteIdentifier((string) $column['name']),
        $columns
    );
    $rows = $db->query('select * from ' . $identifier)->fetchAll(PDO::FETCH_ASSOC);

    $output[] = '-- ' . $table . ': ' . count($rows) . ' baris';
    $output[] = 'TRUNCATE TABLE ' . $identifier . ' RESTART IDENTITY CASCADE;';

    foreach ($rows as $row) {
        $values = array_map($quoteValue, array_values($row));
        $output[] = 'INSERT INTO ' . $identifier . ' (' . implode(', ', $columnNames) . ') VALUES (' . implode(', ', $values) . ');';
    }

    $output[] = '';
}

$output[] = 'COMMIT;';

file_put_contents($outputPath, implode(PHP_EOL, $output) . PHP_EOL);
echo "Export selesai: {$outputPath}" . PHP_EOL;
