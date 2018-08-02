from selenium import webdriver
from bs4 import BeautifulSoup
from datetime import datetime
import pymysql
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.detach(), encoding = 'utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.detach(), encoding = 'utf-8')

class db_controller:
    def __init__(self):
        self.connection = pymysql.connect(host='localhost', user='root', password='12301230', db='lyg', charset='utf8')
        # self.connection = pymysql.connect(host='localhost', user='rid6538259_id6538259_rootoot', password='12301230', db='id6538259_id6538259_lyg', charset='utf8')
        self.input_text = self.set_input_text_from_db()

    def __del__(self):
        self.connect_close()

    def write_html_db(self, _date, _html_str):
        self.curs= self.connection.cursor()
        self.curs.execute("insert into today_question (question_htm, date) values('"+_html_str+"','"+_date+"')")
        self.connection.commit()

    def connect_close(self):
        self.connection.close()

    def set_input_text_from_db(self):
        self.curs= self.connection.cursor()
        self.curs.execute("select request_date from request_date_trash where ID=1")
        rows = self.curs.fetchall()
        refined_date = (datetime.strftime(rows[0][0],'%Y.%m.%d'))
        return refined_date

    def get_input_text(self):
        return self.input_text



dbcon = db_controller()
input_text = dbcon.get_input_text()
#DB에서 유저가 입력한 날짜를 받아온다.
file = open('temp.txt','a+t',encoding='utf8')
file.write(input_text)
file.close()

# driverpath = (os.getcwd())
# driverpath +='\yg\chromedriver.exe'
#
# options = webdriver.ChromeOptions()
#
# # options.add_argument('headless')
# # driver = webdriver.Chrome(driverpath,chrome_options=options)
#
# driver = webdriver.Chrome(driverpath)
#
# driver.get('https://cafe.naver.com/majak?iframe_url=/ArticleList.nhn%3Fsearch.clubid=12152366%26search.menuid=58%26search.boardtype=L')
#
# driver.find_element_by_id('gnb_login_button').click()
# driver.find_element_by_id('id').send_keys('young2too2')
# driver.find_element_by_id('pw').send_keys('tkrjs22tl!')
# driver.find_element_by_xpath('//*[@id="frmNIDLogin"]/fieldset/input').click()
# driver.implicitly_wait(3)
# driver.find_element_by_xpath('//*[@id="frmNIDLogin"]/fieldset/span[2]/a').click()
#
# driver.switch_to_frame('cafe_main')
# title_elements = driver.find_elements_by_css_selector("a.m-tcol-c")
#
# searched_text = ''
#
# for ttt in title_elements:
#     if input_text in ttt.text:
#         searched_text = ttt.text
#         break;
#
# driver.find_element_by_link_text(searched_text).click()
# elem = driver.find_element_by_id('tbody').get_attribute('innerHTML')
#
# dbcon.write_html_db(input_text,elem)
# dbcon.connect_close()
