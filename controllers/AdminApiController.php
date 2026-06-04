<?php
class AdminApiController
{
    private UserModel $users;
    private ReviewModel $reviews;
    private FoodExperienceModel $foodExp;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->reviews = new ReviewModel();
        $this->foodExp = new FoodExperienceModel();
    }

    public function deleteMember(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        if (!$this->users->deleteMember($id)) {
            Security::json(['ok' => false, 'error' => 'Member not found.'], 404);
        }
        Security::json(['ok' => true]);
    }

    public function deleteReview(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        if (!$this->reviews->delete($id)) {
            Security::json(['ok' => false, 'error' => 'Review not found.'], 404);
        }
        Security::json(['ok' => true]);
    }

    public function deleteFoodPost(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $this->foodExp->deletePost($id);
        Security::json(['ok' => true]);
    }

    public function deleteFoodComment(): void
    {
        Auth::requireAdmin();
        $id = (int) ($_POST['id'] ?? 0);
        $this->foodExp->deleteComment($id);
        Security::json(['ok' => true]);
    }
}
