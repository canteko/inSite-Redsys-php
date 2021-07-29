<?php
	if(!class_exists('RESTGenericXml')){
		include_once $GLOBALS["REDSYS_API_PATH"]."/Utils/RESTAnnotation.php";
	
		abstract class RESTGenericXml {	
			
			private function setPropertyValue(ReflectionProperty $prop, $value){
				$nombreSetter="set".strtoupper(substr($prop->getName(),0,1)).substr($prop->getName(),1,strlen($prop->getName())-1);
				$setter=new ReflectionMethod(get_class($this),$nombreSetter);
				if($setter){
					$setter->invoke($this,$value);
				}
			}
			
			private function getPropertyValue(ReflectionProperty $prop){
				$resultado=NULL;
				
				$nombreSetter="get".strtoupper(substr($prop->getName(),0,1)).substr($prop->getName(),1,strlen($prop->getName())-1);
				$setter=new ReflectionMethod(get_class($this),$nombreSetter);
				if($setter){
					$resultado=$setter->invoke($this);
				}
				
				return $resultado;
			}
			
			public function getTagContent($tag, $xml){
				$retorno=NULL;
				
				if($tag && $xml){
					$ini=strpos($xml, "<".$tag.">");
					$fin=strpos($xml, "</".$tag.">");
					if($ini!==false && $fin!==false){
						$ini=$ini+strlen("<".$tag.">");
						if($ini<=$fin){
							$retorno=substr($xml, $ini, $fin-$ini);
						}
					}
				}
					
				return $retorno;
			}
			
			public function parseXml($xml){
				$thisClass=new ReflectionClass(get_class($this));
				$thisTag=RESTAnnotation::getXmlElem($thisClass);
				if($thisTag !== NULL){
					$thisContent=$this->getTagContent($thisTag, $xml);
					if($thisContent !== NULL){
						foreach($thisClass->getProperties() as $prop){
							$xmlClass=RESTAnnotation::getXmlClass($prop);
							if($xmlClass !== NULL){
								$propClass=new ReflectionClass($xmlClass);
								$obj=$propClass->newInstance();
								
								$propClass->getMethod("parseXml")->invoke($obj,$thisContent);
								
								$this->setPropertyValue($prop, $obj);

								$xmlElem=RESTAnnotation::getXmlElem($propClass);
								$thisContent=str_replace("<".$xmlElem.">".$this->getTagContent($xmlElem, $thisContent)."</".$xmlElem.">","",$thisContent);
							}
							else{
								$xmlElem=RESTAnnotation::getXmlElem($prop);
								if($xmlElem !== NULL){
									$tagContent=$this->getTagContent($xmlElem, $thisContent);
									if($tagContent !== NULL){
										$this->setPropertyValue($prop, $tagContent);
										$thisContent=str_replace("<".$xmlElem.">".$tagContent."</".$xmlElem.">","",$thisContent);
									}
								}
							}
						}
					}
				}
			}
	
			public function toXml(){
				$xml="";
				$thisClass=new ReflectionClass(get_class($this));
				$thisTag=RESTAnnotation::getXmlElem($thisClass);
				if($thisTag !== NULL){
					$xml.="<".$thisTag.">";
					foreach($thisClass->getProperties() as $prop){
						$xmlClass=RESTAnnotation::getXmlClass($prop);//Aquí da NULL en RETORNOXML
						if($xmlClass !== NULL){
							$obj=$this->getPropertyValue($prop);
							if($obj !== NULL){
								$propClass=new ReflectionClass($xmlClass);
								$xml.=$propClass->getMethod("toXml")->invoke($obj);
							}
						}
						else{
							$xmlElem=RESTAnnotation::getXmlElem($prop);
							if($xmlElem !== NULL){						//XML NO DEBERÍA DE SER NULL
								$obj=$this->getPropertyValue($prop);
								if($obj !== NULL)
									$xml.="<".$xmlElem.">". (gettype($obj) == "array" ? json_encode($obj) : $obj) ."</".$xmlElem.">";
							}
						}
					}
					try{
						$params=$thisClass->getProperty("parameters");//RETORNOXML REVISAR
						if($params){
							$valores=$this->getPropertyValue($params);
							
							if($valores!=null){
								foreach($valores as $key=>$value){
									$xml.="<".$key.">".$value."</".$key.">";								
								}
							}
						}
					} catch(Exception $e){}
					$xml.="</".$thisTag.">";
				}

				return $xml;
			}


			public function toJson(){
				//die(json_encode($this->toJsonWithArray(array())));
				$this->toJsonWithArray(array())["DS_MERCHANT_MERCHANTCODE"]    ;
				
				$asd = json_encode($this->toJsonWithArray(array()));
				return json_encode($this->toJsonWithArray(array()));
			}
				
			public function toJsonWithArray($arr){
				$thisClass=new ReflectionClass(get_class($this));
				$thisTag=RESTAnnotation::getXmlElem($thisClass);
				if($thisTag !== NULL){
					foreach($thisClass->getProperties() as $prop){
						$xmlClass=RESTAnnotation::getXmlClass($prop);
						if($xmlClass !== NULL){
							$xmlElem=RESTAnnotation::getXmlElem($prop);
							$obj=$this->getPropertyValue($prop);
							if($obj !== NULL && $xmlElem !== NULL){
								$propClass=new ReflectionClass($xmlClass);
								$val=$propClass->getMethod("toJsonWithArray")->invoke($obj,array());
								$arr[$xmlElem]=$val;
							}
						}
						else{
							$xmlElem=RESTAnnotation::getXmlElem($prop);
							if($xmlElem !== NULL){
								$obj=$this->getPropertyValue($prop);
								if($obj !== NULL)
									$arr[$xmlElem]=$obj;
							}
						}
					}
						
					try{
						$params=$thisClass->getProperty("parameters");
						if($params){
							$valores=$this->getPropertyValue($params);
								
							if($valores!=null){
								foreach($valores as $key=>$value){
									$arr[$key]=$value;
								}
							}
						}
					} catch(Exception $e){}
						
					return $arr;
				}
			}
			
			public function parseJson($json){
				$arr=json_decode($json,true);
				
				$thisClass=new ReflectionClass(get_class($this));
				foreach($thisClass->getProperties() as $prop){
					$xmlClass=RESTAnnotation::getXmlClass($prop);
					if($xmlClass !== NULL){
						$propClass=new ReflectionClass($xmlClass);
						$xmlElem=RESTAnnotation::getXmlElem($prop);
						
						if($xmlElem !== NULL && isset($arr[$xmlElem])){
							$obj=$propClass->newInstance();
		
							$propClass->getMethod("parseJson")->invoke($obj,$arr[$xmlElem]);
		
							$this->setPropertyValue($prop, $obj);
							unset($arr[$xmlElem]);
						}
					}
					else{
						$xmlElem=RESTAnnotation::getXmlElem($prop);
						if($xmlElem !== NULL && isset($arr[$xmlElem])){
							$tagContent=$arr[$xmlElem];
							if($tagContent !== NULL){
								$this->setPropertyValue($prop, $tagContent);
								unset($arr[$xmlElem]);
							}
						}
					}
				}
			}
		}

}














