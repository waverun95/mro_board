<?php
    class MyClass {
        const PRINCIPAL = 'Kim';
        static $students = "30명";
        static $boy = "15명";
        static $girl = "15명";

        public function notice(){
            echo "교장은 " . self::PRINCIPAL . "이다.";
        }

        public function event(){
            echo "3월 10일은 운동회";
        }

    }

    $classname = 'Myclass';
   // echo $classname::$students . "<br>";
    echo $classname::notice() . "<br>";

    //echo Myclass::$students . "<br>";
    //echo Myclass::event() . "<br>";

?>