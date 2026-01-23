<?php
namespace App\Controllers;
use Controller;
use App\Repositories\ArticleRepository;
use App\Repositories\EventRepository;
use Auth;

class ArticleController extends Controller{
    private ArticleRepository $articleRepository;

    public function __construct(){
        parent::__construct();
        $this->articleRepository = new ArticleRepository();
    }
    public function index(int $id): void{
        $articles = $this->articleRepository->getByClub($id);
        $this->renderWithLayout('articles/index', [
            'articles' => $articles,
            'clubId'   => $id
        ]);
    }
    
    





    public function create(): void{
        Auth::check();
        if (!Auth::isPresident()) {
            $this->redirect('/');
        }
        $this->renderWithLayout('articles/create');
    }
    public function store(): void{
        Auth::check();
        if (!Auth::isPresident()) {
            $this->redirect('/');
        }
        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $eventId = intval($_POST['event_id'] ?? 0);

        if ($title === '' || $content === '' || $eventId <= 0) {
            $_SESSION['error'] = "Tous les champs sont obligatoire";
            $this->redirect('/articles/create');
        }
        $clubId   = Auth::clubId();
        $authorId = Auth::id();

        $imagesArray = [];
        if (!empty($_POST['images'])) {
            $decoded = json_decode($_POST['images'], true);
            if (is_array($decoded)) {
                $imagesArray = $decoded;
            }
        }

        $this->articleRepository->create([
            'title'=> $title,
            'content'=> $content,
            'images'=> json_encode($imagesArray),
            'event_id'=> $eventId,
            'club_id'=> $clubId,
            'author_id'=> $authorId
        ]);

        $this->redirect("/clubs/{$clubId}/articles");
    }

    public function show(int $id): void{
        Auth::check();
        $article = $this->articleRepository->find($id);
        if (!$article) {
            http_response_code(404);
            echo "Article not found!";
            return;
        }
        $eventRepo = new EventRepository();
        $event= $eventRepo->find($article['event_id']);
        $images = [];
        if (!empty($article['images'])) {
            $decoded = json_decode($article['images'], true);
            if (is_array($decoded)) {
                $images = $decoded;
            }
        }
        $this->renderWithLayout('articles/show', [
            'article'=> $article,
            'event' => $event,
            'images'=> $images,
            'canEdit'=> Auth::isPresident(),
            'canDelete'=> Auth::isPresident() || ($_SESSION['user']['role'] === 'admin')
        ]);
    }
    public function edit(int $id): void{
        Auth::check();
        if (!Auth::isPresident()) {
            $this->redirect('/');
        }
        $article = $this->articleRepository->find($id);
        if (!$article) {
            http_response_code(404);
            echo "Article non trouvé";
            return;
        }
        $this->renderWithLayout('articles/edit', ['article' => $article]);
    }
    public function update(int $id): void{
        Auth::check();
        if (!Auth::isPresident()) {
            $this->redirect('/');
        }
        $title= trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($title==='' || $content=== '') {
            $_SESSION['error'] = "Tous les champs sont obliguatoires";
            $this->redirect("/articles/{$id}/edit");
        }

        $imagesArray =[];
        if (!empty($_POST['images'])) {
            $decoded = json_decode($_POST['images'], true);
            if (is_array($decoded)) {
                $imagesArray = $decoded;
            }
        }
        $this->articleRepository->update($id, [
            'title' => $title,
            'content' => $content,
            'images' => json_encode($imagesArray)
        ]);
        $clubId = Auth::clubId();
        $this->redirect("/clubs/{$clubId}/articles");
    }
    public function delete(int $id): void{
        Auth::check();
        if (!Auth::isPresident() && $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/');
        }
        $this->articleRepository->delete($id);
        $clubId = Auth::clubId();
        $this->redirect("/clubs/{$clubId}/articles");
    }
}
