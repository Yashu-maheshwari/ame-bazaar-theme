$connectionString = "Server=DESKTOP-APTK2JJ;Database=Raintech_DB1;User Id=SA;Password=12345;"
$connection = New-Object System.Data.SqlClient.SqlConnection
$connection.ConnectionString = $connectionString
$connection.Open()

$query = "SELECT TOP 1 * FROM Product WHERE ProductCode = 'P-3616'"
$command = $connection.CreateCommand()
$command.CommandText = $query
$reader = $command.ExecuteReader()
while ($reader.Read()) {
    for ($i = 0; $i -lt $reader.FieldCount; $i++) {
        Write-Host "$($reader.GetName($i)): $($reader.GetValue($i))"
    }
}
$reader.Close()

Write-Host "`n=== LIST ALL COLUMNS IN PRODUCT TABLE ==="
$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Product'"
$command.CommandText = $query
$reader = $command.ExecuteReader()
while ($reader.Read()) {
    Write-Host $($reader.GetValue(0))
}
$reader.Close()

Write-Host "`n=== LIST TABLES WITH RELEVANT NAMES ==="
$query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND (TABLE_NAME LIKE '%Attr%' OR TABLE_NAME LIKE '%Color%' OR TABLE_NAME LIKE '%Size%' OR TABLE_NAME LIKE '%Category%' OR TABLE_NAME LIKE '%Var%')"
$command.CommandText = $query
$reader = $command.ExecuteReader()
while ($reader.Read()) {
    Write-Host $($reader.GetValue(0))
}
$reader.Close()

$connection.Close()
