<?php
class F1SensorData {
    private $dataPath = __DIR__ . '/f1_data/';
    private $cacheTime = 5; // sekundy
    
    public function __construct() {
        // Utwórz folder jeśli nie istnieje
        if (!file_exists($this->dataPath)) {
            mkdir($this->dataPath, 0755, true);
        }
    }
    
    public function getCurrentSession() {
        $file = $this->dataPath . 'session_status.json';
        if (!file_exists($file)) return null;
        
        $data = json_decode(file_get_contents($file), true);
        return $this->isLive() ? $data : null;
    }
    
    public function getDriverPositions() {
        $file = $this->dataPath . 'driver_positions.json';
        if (!file_exists($file)) return [];
        
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        
        if (!is_array($data)) return [];
        
        // Posortuj według pozycji
        usort($data, function($a, $b) {
            $posA = isset($a['position']) ? (int)$a['position'] : 999;
            $posB = isset($b['position']) ? (int)$b['position'] : 999;
            return $posA <=> $posB;
        });
        
        return $data;
    }
    
    public function getPitStops() {
        $file = $this->dataPath . 'pit_stops.json';
        if (!file_exists($file)) return [];
        
        $data = json_decode(file_get_contents($file), true);
        return is_array($data) ? $data : [];
    }
    
    public function getTireStatistics() {
        $file = $this->dataPath . 'tire_statistics.json';
        if (!file_exists($file)) return [];
        
        $data = json_decode(file_get_contents($file), true);
        return is_array($data) ? $data : [];
    }
    
    public function getRaceControl() {
        $file = $this->dataPath . 'race_control.json';
        if (!file_exists($file)) return [];
        
        $data = json_decode(file_get_contents($file), true);
        return is_array($data) ? $data : [];
    }
    
    public function isLive() {
        // Sprawdź czy pliki są aktualne (nie starsze niż 30 sekund)
        $files = glob($this->dataPath . '*.json');
        if (empty($files)) return false;
        
        $latestFile = 0;
        foreach ($files as $file) {
            $mtime = filemtime($file);
            if ($mtime > $latestFile) $latestFile = $mtime;
        }
        
        $now = time();
        return ($now - $latestFile) < 30; // dane nie starsze niż 30 sekund
    }
    
    public function getLastUpdate() {
        $files = glob($this->dataPath . '*.json');
        $latest = 0;
        
        foreach ($files as $file) {
            $mtime = filemtime($file);
            if ($mtime > $latest) $latest = $mtime;
        }
        
        return $latest;
    }
    
    public function getDataPath() {
        return $this->dataPath;
    }
}
?>