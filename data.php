<!--Integration for admission -->
<!--Insert nyo to sa body ng code nyo-->

    <script>
        let studentsData = [];

        async function fetchStudents() {
            const container = document.getElementById('students-container');

            try {
                const response = await fetch('https://admission.bcpsms3.com/api_students.php');
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const students = await response.json();

                if (!Array.isArray(students) || students.length === 0) {
                    container.innerHTML = '<p class="text-danger text-center">No students found.</p>';
                    return;
                }

                studentsData = students;
                displayStudents(students);
            } catch (error) {
                container.innerHTML = `<p class="text-danger text-center">Error fetching students data: ${error.message}</p>`;
            }
        }

        function displayStudents(students) {
            const container = document.getElementById('students-container');
            container.innerHTML = '';

            const table = document.createElement('table');
            table.className = 'table table-striped table-bordered text-center';

            const thead = document.createElement('thead');
            thead.innerHTML = `
                <tr class="table-dark">
                    <th>Student Number</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Contact Number</th>
                    <th>Year Level</th>
                    <th>Sex</th>
                    <th>Department Code</th>
                </tr>
            `;
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            students.forEach(student => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.student_number}</td>
                    <td>${student.first_name}</td>
                    <td>${student.middle_name || 'N/A'}</td>
                    <td>${student.last_name}</td>
                    <td>${student.contact_number}</td>
                    <td>${student.year_level}</td>
                    <td>${student.sex}</td>
                    <td>${student.department_code}</td>
                `;
                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            container.appendChild(table);
        }

        async function saveStudents() {
            const statusMessage = document.getElementById('status-message');

            if (studentsData.length === 0) {
                statusMessage.innerHTML = '<span class="text-danger">No student data available to save.</span>';
                return;
            }

            try {
                const response = await fetch('insert_students.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ students: studentsData }),
                });

                const result = await response.json();
                if (result.success) {
                    statusMessage.innerHTML = '<span class="text-success">Students successfully stored in the database!</span>';
                } else {
                    throw new Error(result.message || 'Failed to save students.');
                }
            } catch (error) {
                statusMessage.innerHTML = `<span class="text-danger">Error: ${error.message}</span>`;
            }
        }

        window.onload = fetchStudents;

        document.getElementById('save-btn').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
        });
        
        document.getElementById('confirm-save-btn').addEventListener('click', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            modal.hide();
            saveStudents();
        });

    </script>
  