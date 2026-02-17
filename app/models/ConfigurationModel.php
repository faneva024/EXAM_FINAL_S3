<?php

namespace app\models;

use Flight;
use PDO;

class ConfigurationModel
{
    public function get(string $cle): ?string
    {
        $stmt = Flight::db()->prepare("SELECT valeur FROM BNGRC_Configuration WHERE cle = :cle");
        $stmt->execute(['cle' => $cle]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['valeur'] : null;
    }

    public function set(string $cle, string $valeur): bool
    {
        $stmt = Flight::db()->prepare("
            INSERT INTO BNGRC_Configuration (cle, valeur) VALUES (:cle, :valeur)
            ON DUPLICATE KEY UPDATE valeur = :valeur2
        ");
        return $stmt->execute([
            'cle' => $cle,
            'valeur' => $valeur,
            'valeur2' => $valeur,
        ]);
    }

    public function getFraisAchat(): float
    {
        $val = $this->get('frais_achat_pourcent');
        return $val !== null ? (float)$val : 10.0;
    }
}
