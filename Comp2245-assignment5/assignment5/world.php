<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $table = isset($_GET['table']) ? $_GET['table'] : 'countries';
    $search = isset($_GET['search']) ? '%' . strip_tags($_GET['search']) . '%' : '%';
    
    // Prepare statements to prevent SQL injection
    if($table == 'countries') {
        $stmt = $conn->prepare("SELECT name, continent, independence_year, head_of_state 
                               FROM countries 
                               WHERE name LIKE :search");
    } else {
        $stmt = $conn->prepare("SELECT c.name, c.district, c.population, co.name as country_name 
                               FROM cities c 
                               JOIN countries co ON c.country_code = co.code 
                               WHERE c.name LIKE :search");
    }
    
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(!empty($results)): ?>
        <table>
            <thead>
                <tr>
                    <?php if($table == 'countries'): ?>
                        <th>Name</th>
                        <th>Continent</th>
                        <th>Independence</th>
                        <th>Head of State</th>
                    <?php else: ?>
                        <th>Name</th>
                        <th>District</th>
                        <th>Population</th>
                        <th>Country</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <?php if($table == 'countries'): ?>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['continent']) ?></td>
                            <td><?= htmlspecialchars($row['independence_year']) ?></td>
                            <td><?= htmlspecialchars($row['head_of_state']) ?></td>
                        <?php else: ?>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['district']) ?></td>
                            <td><?= number_format($row['population']) ?></td>
                            <td><?= htmlspecialchars($row['country_name']) ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-results">No results found</p>
    <?php endif;

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>