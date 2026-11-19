<?php
class User
{
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function findByUsername(string $username)
    {
        $s = $this->db->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $s->execute([$username]);
        return $s->fetch();
    }
}
