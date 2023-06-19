<!-- Owner: github.com/adxdy !-->
<!-- Do not copy this work without permission !-->

<!DOCTYPE html>
<html>
<head>
    <title>CGPA Calculator</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-fork-ribbon-css/0.2.3/gh-fork-ribbon.min.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/style.css">
</head> <!-- /.- -.. -..- -.. -.--/ -->
<body>
    <a class="github-fork-ribbon right-top fixed" href="https://github.com/adxdy/CGPA-Calculator" data-ribbon="Github Repo" title="Visit GitHub Repo">Visit Gihub Repo</a>
    
    <div class="container">
        <h1>CGPA Calculator</h1>
        <form method="POST" action="">
            <label for="courses">My Recent Finished Term...</label>
            <select id="courses" name="courses">
                <?php
                for ($i = 1; $i <= 4; $i++) {
                    for ($j = 1; $j <= 2; $j++) {
                        $s = ($i - 1) * 2 + $j;
                        $value = "$i.$j";
                        echo "<option value='$s'>$value</option>";
                    }
                }
                ?>
            </select>
            <div id="course-inputs"></div>
            <!-- /.- -.. -..- -.. -.--/ -->
            <input type="submit" name="calculate" value="Calculate CGPA">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['calculate'])) {
                $cgpaArray = isset($_POST['cgpa']) ? $_POST['cgpa'] : array();
                $creditArray = isset($_POST['credit']) ? $_POST['credit'] : array();

                $numCourses = count($cgpaArray);

                $weightedSum = 0;
                $totalCredits = 0;

                for ($i = 0; $i < $numCourses; $i++) {
                    if (is_numeric($cgpaArray[$i]) && is_numeric($creditArray[$i])) {
                        $weightedSum += $cgpaArray[$i] * $creditArray[$i];
                        $totalCredits += $creditArray[$i];
                    }
                }
                // <!-- /.- -.. -..- -.. -.--/ -->
                if ($totalCredits != 0) {
                    $cgpa = $weightedSum / $totalCredits;
                    $formattedCgpa = number_format($cgpa, 2);
                    echo "<h2>CGPA: $formattedCgpa</h2>";
                } else {
                    echo "<h2>Error: Enter valid inputs</h2>";
                }
            }
        }
        ?>

        <p class="note">TRCH = Total Registered Credit Hour (Not Earned)</p>
    </div>
    <!-- /.- -.. -..- -.. -.--/ -->
    <script>

        document.getElementById('courses').addEventListener('change', function() {
            var numCourses = this.value;
            var courseInputs = document.getElementById('course-inputs');
            courseInputs.innerHTML = '';

            var term = 1;
            var subTerm = 1;

            var cgpaValues = <?php echo json_encode(isset($_POST['cgpa']) ? $_POST['cgpa'] : []); ?>;
            var creditValues = <?php echo json_encode(isset($_POST['credit']) ? $_POST['credit'] : []); ?>;

            for (var i = 1; i <= numCourses; i++) {
                var inputGroup = document.createElement('div');
                inputGroup.className = 'input-group';
                /*.- -.. -..- -.. -.--*/
                var cgpaInput = document.createElement('input');
                cgpaInput.type = 'number';
                cgpaInput.name = 'cgpa[]';
                cgpaInput.step = '0.01';
                cgpaInput.min = '1.50';
                cgpaInput.max = '4.00';
                cgpaInput.placeholder = 'CGPA [' + term + '.' + subTerm + ']';
                cgpaInput.value = cgpaValues[i-1] || '';
                inputGroup.appendChild(cgpaInput);
                /*.- -.. -..- -.. -.--*/
                var creditInput = document.createElement('input');
                creditInput.type = 'number';
                creditInput.name = 'credit[]';
                creditInput.step = '0.01';
                creditInput.min = '10.00';
                creditInput.max = '40.00';
                creditInput.placeholder = 'TRCH [' + term + '.' + subTerm + ']';
                creditInput.value = creditValues[i-1] || '';
                inputGroup.appendChild(creditInput);
                /*.- -.. -..- -.. -.--*/
                courseInputs.appendChild(inputGroup);

                subTerm++;

                if (subTerm > 2) {
                    term++;
                    subTerm = 1;
                }
            }
        });

        var coursesSelect = document.getElementById('courses');
        var selectedValue = <?php echo json_encode(isset($_POST['courses']) ? $_POST['courses'] : ''); ?>;
        if (selectedValue) {
            coursesSelect.value = selectedValue;
        }
        /*.- -.. -..- -.. -.--*/
        document.getElementById('courses').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
