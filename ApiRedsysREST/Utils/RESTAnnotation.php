<?php
if(!class_exists('RESTAnnotation')){
	class RESTAnnotation{
		
		private static function getAnnotation($object, $name){
			$retorno = NULL;
			
			$doc = $object->getDocComment();
			preg_match('#@'.$name.'=(.+)(\s)*(\r)*\n#s', $doc, $annotations);
			if(is_array($annotations) && sizeof($annotations)>=2){
				$retorno=trim(explode(" ",$annotations[1])[0]);
			}
			
			return $retorno;
		}	
		
		public static function getXmlElem($object){
			return RESTAnnotation::getAnnotation($object, "XML_ELEM");
		}
		
		public static function getXmlClass($object){
			return RESTAnnotation::getAnnotation($object, "XML_CLASS");
		}
	}
}