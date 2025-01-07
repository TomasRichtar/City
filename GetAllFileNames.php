<?php

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// GitHub repository information
$github_owner = "TomasRichtar"; // Změňte na vaše GitHub uživatelské jméno
$github_repo = "AssetsBundles";  // Změňte na název vašeho repository
$github_dir = htmlspecialchars($_POST["githubdir"]); // Složka ke čtení

// GitHub API URL for fetching contents of the directory
$url = "https://api.github.com/repos/$github_owner/$github_repo/contents/$github_dir";

// Create a cURL handle to make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: PHP-Script" // GitHub vyžaduje hlavičku User-Agent
]);

// Execute the request and decode the JSON response
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $files = json_decode($response, true);

    if (isset($files["message"])) {
        // GitHub API error response
        echo "Error: " . $files["message"];
    } else {
        // List all files in the directory
        foreach ($files as $file) {
            if ($file["type"] === "file") {
                echo $file["name"] . "\n";
            }
        }
    }
} else {
    echo "Error fetching directory contents.";
}

?>
