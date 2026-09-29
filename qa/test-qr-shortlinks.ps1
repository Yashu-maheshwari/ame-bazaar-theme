$knownSku = "P-3660"
$unknownSku = "UNKNOWN-999"

Write-Host "--- TEST PLAN ---"
Write-Host "Test 1: Known SKU ($knownSku)"
Write-Host "  Request: GET /p/$knownSku"
Write-Host "  Expected: HTTP 302 Redirect to WooCommerce Product Permalink"

Write-Host "Test 2: Unknown SKU ($unknownSku)"
Write-Host "  Request: GET /p/$unknownSku"
Write-Host "  Expected: HTTP 302 Redirect to /?s=$unknownSku&post_type=product"
