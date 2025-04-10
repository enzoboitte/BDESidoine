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
        $query = "SELECT `idE`, `titre`, `phrase`, `debut`, `img`, `type` FROM `event` WHERE `debut` < CURRENT_TIME() ORDER BY `debut` ASC;";
        $result = $this->F_cGetDB()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $this->events = [];

        foreach ($result as $row) {
            $this->events[] = new CEvent($row['idE'], $row['titre'], $row['phrase'], $row['debut'], $row['img'], $row['type']);
        }
    }

    private function loadEventsLast()
    {
        $query = "SELECT `idE`, `titre`, `phrase`, `debut`, `img`, `type` FROM `event` WHERE `debut` >= CURRENT_TIME() ORDER BY `debut` ASC;";
        $result = $this->F_cGetDB()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $this->events = [];

        foreach ($result as $row) {
            $this->events[] = new CEvent($row['idE'], $row['titre'], $row['phrase'], $row['debut'], $row['img'], $row['type']);
        }
    }

    // load all event
    public function F_vLoadAllEvents()
    {
        $query = "SELECT `idE`, `titre`, `phrase`, `debut`, `img`, `type` FROM `event` ORDER BY `debut` ASC;";
        $result = $this->F_cGetDB()->prepare($query);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $this->events = [];

        foreach ($result as $row) {
            $this->events[] = new CEvent($row['idE'], $row['titre'], $row['phrase'], $row['debut'], $row['img'], $row['type']);
        }

        //die(var_dump($this->events));
    }

    // method to get all events
    public function getEvents()
    {
        return $this->events;
    }

    // method to get an event by id F_lGetEvent
    public function F_lGetEvent($idE)
    {
        foreach ($this->events as $event) {
            if ($event->getIdE() == $idE) {
                return $event;
            }
        }
        return null;
    }

    // method to remove an event from the database F_bRmEvent
    public function F_bRmEvent($idE): bool
    {
        $query = "DELETE FROM `event` WHERE `idE` = :idE;";
        $result = $this->F_cGetDB()->prepare($query);
        $result->bindParam(':idE', $idE, PDO::PARAM_INT);
        return $result->execute();
    }

    // method to add an event to the database F_bAddEvent
    public function F_bAddEvent($titre, $debut, $img, $type): bool
    {
        $query = "INSERT INTO `event` (`titre`, `phrase`, `debut`, `img`, `type`) VALUES (:titre, '', :debut, :img, :type);";
        $result = $this->F_cGetDB()->prepare($query);
        $result->bindParam(':titre', $titre, PDO::PARAM_STR);
        $result->bindParam(':debut', $debut, PDO::PARAM_STR);
        $result->bindParam(':img', $img, PDO::PARAM_STR);
        $result->bindParam(':type', $type, PDO::PARAM_STR);

        $res = $result->execute();

        $this->F_vLoadAllEvents();

        return $res;
    }

    // method to update an event in the database F_bUpdateEvent
    public function F_bUpdateEvent($idE, $titre, $debut, $img, $type): bool
    {
        $query = "UPDATE `event` SET `titre` = :titre, `debut` = :debut, `img` = :img, `type` = :type WHERE `idE` = :idE;";
        $result = $this->F_cGetDB()->prepare($query);
        $result->bindParam(':idE', $idE, PDO::PARAM_INT);
        $result->bindParam(':titre', $titre, PDO::PARAM_STR);
        $result->bindParam(':debut', $debut, PDO::PARAM_STR);
        $result->bindParam(':img', $img, PDO::PARAM_STR);
        $result->bindParam(':type', $type, PDO::PARAM_STR);

        $res = $result->execute();

        $this->F_vLoadAllEvents();

        return $res;
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
    private $type;

    // constructor of class CEvent
    public function __construct($idE, $titre, $phrase, $debut, $img, $type)
    {
        $this->idE = $idE;
        $this->titre = $titre;
        $this->phrase = $phrase;
        $this->debut = $debut;
        $this->img = $img;
        $this->type = $type;
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

    public function getType()
    {
        return $this->type;
    }


    // toJson Array
    public function toJson()
    {
        return [
            "idE" => $this->idE,
            "titre" => $this->titre,
            "phrase" => $this->phrase,
            "debut" => $this->debut,
            "img" => $this->img,
            "type" => $this->type
        ];
    }
    // toJson String
    public function toJsonString()
    {
        return json_encode($this->toJson());
    }
}