<?php
    function getContacts(): array
    {
        $data = file_get_contents('contacts.json');
        return json_decode($data, true);
    }

    function saveContacts(array $contacts): void
    {
        file_put_contents('contacts.json', json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add']) && isset($_POST['surname']) && isset($_POST['name']) && isset($_POST['lastName']))
    {
        $surname = $_POST['surname'];
        $name = $_POST['name'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'] ?? '';
        $contacts = getContacts();
        $contacts[] = ['surname' => $surname, 'name' => $name, 'lastName' => $lastName, 'email' => $email];
        saveContacts($contacts);

        header("Location: ./index.php");
        exit();
    }

    if (isset($_GET['delete']))
    {
        $index = (int)$_GET['delete'];
        $contacts = getContacts();
        unset($contacts[$index]);
        saveContacts($contacts);

        header("Location: ./index.php");
        exit();
    }

    $contacts = getContacts();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1 class="h1">Заполните анкету</h1>

    <div class="main">
        <h2>Добавить контакт</h2>
        <form class="form" action="./index.php" method="POST" accept-charset="UTF-8">
            <div class="mb-3">
                <label class="form-label">Введите фамилию</label>
                <input type="text" class="form-control" name="surname" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Введите имя</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Введите отчество</label>
                <input type="text" class="form-control" name="lastName" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Введите email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-btn">
                <button type="submit" class="btn btn-dark" name="add">Добавить</button>
            </div>
        </form>
    </div>

    <div class="list">
        <h2>Список контактов</h2>
        <table class="table table-dark table-sm">
            <thead>
                <tr class="tr">
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>отчество</th>
                    <th>Email</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($contacts as $index => $contact): ?>
                    <tr>
                        <td class="td"><?php echo htmlspecialchars($contact['surname']); ?></td>
                        <td class="td"><?php echo htmlspecialchars($contact['name']); ?></td>
                        <td class="td"><?php echo htmlspecialchars($contact['lastName']); ?></td>
                        <td class="td"><?php echo htmlspecialchars($contact['email']); ?></td>
                        <td class="td">
                            <a href="?delete=<?php echo $index; ?>"><button class="btn btn-secondary">Удалить</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>