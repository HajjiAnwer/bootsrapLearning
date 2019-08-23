
<?php
 ini_set('display_errors', 1);
function loadTemplate($templateFileName, $variables = [])
{
    extract($variables);
    ob_start();
    include $templateFileName;
    return ob_get_clean();
}
try 
{
    include 'database_connection.php';
    include 'DatabaseTable.php';
    $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
    $authorsTable = new DatabaseTable($pdo, 'author', 'id');
    //if no route variable is set, use 'joke/home'
    $route = $_GET['route'] ?? 'joke/home';
    if ($route == strtolower($route)) 
    {
        if ($route === 'joke/list') 
        {
            include 'JokeController.php';
            $controller = new JokeController($jokesTable,$authorsTable);
            $page = $controller->list();
        } 
        elseif ($route === 'joke/home') 
        {
            include 'JokeController.php';
            $controller = new JokeController($jokesTable,$authorsTable);
            $page = $controller->home();
        } 
        elseif ($route === 'joke/edit') 
        {
            include 'JokeController.php';
            $controller = new JokeController($jokesTable,$authorsTable);
            $page = $controller->edit();
        } 
        elseif ($route === 'joke/delete') 
        {
            include 'JokeController.php';
            $controller = new JokeController($jokesTable,$authorsTable);
            $page = $controller->delete();
        } 
        elseif ($route === 'register') 
        {
            include 'RegisterController.php';
            $controller = new RegisterController($authorsTable);
            $page = $controller->showForm();
        }
    } 
    else 
    {
        http_response_code(301);
        header('location: index.php?route=' . strtolower($route));
    }
    $title = $page['title'];
    if (isset($page['variables'])) 
    {
        $output = loadTemplate($page['template'],$page['variables']);
    } 
    else 
    {
        $output = loadTemplate($page['template']);
    }
} 
catch (PDOException $e) 
{
    $title = 'An error has occurred';
    $output = 'Database error: ' . 
    $e->getMessage() . ' in '. 
    $e->getFile() . ':' . $e->getLine();
}
    include 'head.html.php';
    include 'footer.html.php';
?>