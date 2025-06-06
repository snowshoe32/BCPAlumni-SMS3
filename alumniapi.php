<!-- This could be in student-data.php or any other frontend file -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Data Viewer</title>
</head>
<body>
    <h1>Alumni Data (Formatted List)</h1>
    <div id="alumni-list">
        Loading alumni data...
    </div>

    <h2>Raw API Response (Pretty Printed)</h2>
    <pre id="json-response" style="background-color: #f4f4f4; border: 1px solid #ddd; padding: 10px; white-space: pre-wrap; word-wrap: break-word;"></pre>

    <script>
        // The URL for your new API endpoint
        const apiUrl = 'http://localhost/public_html/alumni_system_api.php'; // Corrected API URL

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    // Try to get error message from API if available
                    return response.json().then(errData => {
                        throw new Error(`Network response was not ok: ${response.status} ${response.statusText}. API Error: ${errData.message || 'Unknown API error'}`);
                    }).catch(() => {
                        // Fallback if response.json() fails (e.g., not valid JSON)
                        throw new Error(`Network response was not ok: ${response.status} ${response.statusText}.`);
                    });
                }
                return response.json(); // Parse the JSON from the response
            })
            .then(apiResponse => {
                const jsonResponseDiv = document.getElementById('json-response');
                // Pretty print the raw JSON response
                jsonResponseDiv.textContent = JSON.stringify(apiResponse, null, 2);

                const alumniListDiv = document.getElementById('alumni-list');

                // Check if the response is an array (success case for your API)
                // or an object with an 'error' property
                if (Array.isArray(apiResponse)) {
                    const alumni = apiResponse;
                    if (alumni.length > 0) {
                        let html = '<ul>';
                        alumni.forEach(alum => {
                            // Properties from bcp_sms3_user table as per alumni_system_api.php
                            html += `<li>ID: ${alum.id}, Name: ${alum.name || 'N/A'}, Email: ${alum.email || 'N/A'}, Username: ${alum.username || 'N/A'}, Type: ${alum.user_type || 'N/A'}</li>`;
                        });
                        html += '</ul>';
                        alumniListDiv.innerHTML = html;
                    } else {
                        alumniListDiv.innerHTML = 'No alumni data found.';
                    }
                } else if (apiResponse && apiResponse.error) {
                    // Handle API-specific errors (e.g., "Unauthorized access")
                    console.error('API Error:', apiResponse.error);
                    alumniListDiv.innerHTML = `Error fetching data: ${apiResponse.error}`;
                } else {
                    // Handle other unexpected JSON structures
                    console.error('Unexpected API response structure:', apiResponse);
                    alumniListDiv.innerHTML = 'Unexpected data format received from API.';
                }
            })
            .catch(error => {
                // Handle network errors or other issues with the fetch itself
                console.error('Fetch Error:', error);
                const alumniListDiv = document.getElementById('alumni-list');
                const jsonResponseDiv = document.getElementById('json-response');
                alumniListDiv.innerHTML = `Failed to load data. ${error.message}`;
                jsonResponseDiv.textContent = `Fetch Error: ${error.message}`;
            });
    </script>
</body>
</html>
