<?php
//SELECT `idE`, `titre`, `phrase`, `debut`, `img` FROM `event` where `debut` > CURRENT_TIME() ORDER BY `debut` asc; 
include_once "$G_sRacine/model/PDOModel.php";

// class for representing multiple event objects
class CEvents extends CPDOModel
{
    // private attributes of class CEvents
    private $events = [];

    // constructor of class CEvents
    // constructor of class CEvents for last events
    public function __construct(bool $last = false)
    {
        if ($last) {
            $this->loadEventsLast();
        } else {
            $this->loadEvents();
        }
    }

    // method to load events from the database
    private function loadEvents()
    {
        $query = "SELECT `idE`, `titre`, `phrase`, `debut`, `img` FROM `event` WHERE `debut` < CURRENT_TIME() ORDER BY `debut` ASC;";
        $result = $this->F_cGetDB()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->events[] = new CEvent($row['idE'], $row['titre'], $row['phrase'], $row['debut'], $row['img']);
        }
    }

    private function loadEventsLast()
    {
        $query = "SELECT `idE`, `titre`, `phrase`, `debut`, `img` FROM `event` WHERE `debut` >= CURRENT_TIME() ORDER BY `debut` ASC;";
        $result = $this->F_cGetDB()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $this->events[] = new CEvent($row['idE'], $row['titre'], $row['phrase'], $row['debut'], $row['img']);
        }
    }

    // method to get all events
    public function getEvents()
    {
        return $this->events;
    }
}

// object CEvent
class CEvent
{
    // private attributes of class CEvent
    private $idE;
    private $titre;
    private $phrase;
    private $debut;
    private $img;

    // constructor of class CEvent
    public function __construct($idE, $titre, $phrase, $debut, $img)
    {
        $this->idE = $idE;
        $this->titre = $titre;
        $this->phrase = $phrase;
        $this->debut = $debut;
        $this->img = $img;
    }

    // getter of class CEvent
    public function getIdE()
    {
        return $this->idE;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function getPhrase()
    {
        return $this->phrase;
    }

    public function getDebut()
    {
        return $this->debut;
    }

    public function getImg()
    {
        return $this->img;
    }
}