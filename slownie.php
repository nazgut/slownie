<?php
class slownie {
	private $cyfra_array, $nazwa, $key, $arr_jednosci, $arr_dziesiatki, $arr_setki, $arr_przedzial, $arr_jednostka;
	
	function __construct() {
		$this->arr_jednosci = array(1=>'jeden', 'dwa', 'trzy', 'cztery', 'pięć', 'sześć', 'siedem', 'osiem', 'dziewięć', 'dziesięć', 'jedenaście', 'dwanaście', 'trzynaście', 'czternaście', 'piętnaście', 'szesnaście', 'siedemnaście', 'osiemnaście', 'dziewiętnaście');
		$this->arr_dziesiatki = array(2=>'dwadzieścia', 'trzydzieści', 'czterdzieści', 'pięćdziesiąt', 'sześćdziesiąt', 'siedemdziesiąt', 'osiemdziesiąt', 'dziewięćdziesiąt');
		$this->arr_setki = array(1=>'sto', 'dwieście', 'trzysta', 'czterysta', 'pięćset', 'sześćset', 'siedemset', 'osiemset', 'dziewięćset');
		$this->arr_przedzial = array(2=>array('tysiąc', 'tysiące', 'tysięcy'), array('milion', 'miliony', 'milionów'));
		$this->arr_jednostka = array(array('złoty', 'złote', 'złotych'), array('grosz', 'grosze', 'groszy'));
	}
	
	/*
	(array)$array = dwuwymiarowa tabelka ze zmiennymi
	(int)$przedzial = jaką tabelkę wykorzystać
	*/
	private function miara($array, $przedzial) {
		if ($this->cyfra_array[$this->key-2]==0&&$this->cyfra_array[$this->key-1]==0&&$this->cyfra_array[$this->key]==1) $this->nazwa .= $array[$przedzial][0].' ';
		elseif($this->cyfra_array[$this->key-2]>=1||$this->cyfra_array[$this->key-1]>=1||$this->cyfra_array[$this->key]>4) $this->nazwa .= $array[$przedzial][2].' ';
		else $this->nazwa .= $array[$przedzial][1].' ';
	}
	
	/*
	(int)$prog = na którym jesteśmy etapie sprawdzania (2,1,0) (jedności, dziesiątki, setki)
	(int)$n = index w odpowiedniej tablicy
	(int)$przedzial = dla rozróżnienia czy są to tysiące, miliony itd.
	*/
	private function jakie_slowo($prog, $n, $przedzial) {
		if ($prog==2) {
			if($this->cyfra_array[$this->key-1]!=1) $this->nazwa .= $this->arr_jednosci[$n].' ';
			if ($przedzial>1) { // czyli trzeba dodać końcówkę (tysiące, miliony itp)
				$this->miara($this->arr_przedzial, $przedzial);
			}
		}
		elseif($prog==1) {
			if($n>1) $this->nazwa .= $this->arr_dziesiatki[$n].' ';
			else $this->nazwa .= $this->arr_jednosci[$n.$this->cyfra_array[$this->key+1]].' ';
		}
		else $this->nazwa .= $this->arr_setki[$n].' ';
	}
	
	/*
	(float)$liczba = liczba do zamiany 
	 */ 
	public function pokaz($liczba) {
		$liczba = explode('.', number_format($liczba, 2, '.', ''));
		foreach($liczba as $key=>$l) { // całkowita:ułamkowa
			if ($l>0) { // zamieniamy tylko liczby większe od zera
				$this->cyfra_array = str_split($l); //liczba zamieniona na tablicę
				foreach($this->cyfra_array as $k=>$n) { // sprawdzamy każdą liczbę w łancuchu
					$this->key = $k; // przypisanie obecnego indexu tablicy 
					$dlugosc = strlen($l); // która liczba w łancuchu
					$przedzial = ceil($dlugosc/3); // jednostka rzędu
					$prog = $przedzial*3-$dlugosc; // która liczba z progu
					$this->jakie_slowo($prog, $n, $przedzial); // zamieniamy liczbę na słowo
					$l = substr($l, 1); // wywalamy liczbę z listy
				}
				$this->miara($this->arr_jednostka, $key);
			}
		}
		return $this->nazwa;
	}
}
?>
