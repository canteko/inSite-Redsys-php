import time
import json
import sys
import os

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.keys import Keys
from selenium.common.exceptions import NoAlertPresentException
from selenium.common.exceptions import UnexpectedAlertPresentException
from selenium import webdriver
from webdriver_manager.chrome import ChromeDriverManager
from webdriver_manager.utils import ChromeType
from pprint import pprint

# Abrimos el navegador
driver = webdriver.Chrome(ChromeDriverManager().install())

# Entramos en la web y obtenemos el body
driver.get("https://pre.web.bbva.es/ei/develop/loginBBVANet.html")

# Click para hacer login
driver.find_element_by_css_selector('#client-access-controller').click()

# Entramos en el iframe cambiamos el foco
iframe = driver.find_element_by_css_selector("#login-dialog-iframe")   
driver.switch_to.frame(iframe)

# Escribimos los datos de login
driver.find_element_by_css_selector('input[name="user"]').send_keys("002042003V")
driver.find_element_by_css_selector('input[name="password"]').send_keys("123456")