package main

import (
	"encoding/json"
	"errors"
	"fmt"
	"io"
	"os"
	"time"
)

type Log struct {
	Username    string `json:"username"`
	LoginStatus bool   `json:"loginStatus"`
	LogDate     string `json:"date"`
}

type User struct {
	Id       int    `json:"id"`
	Name     string `json:"name"`
	Surname  string `json:"surname"`
	Username string `json:"username"`
	Password string `json:"password"`
}

type Users struct {
	UsersList []User `json:"usersList"`
}

var usersList []User
var activeUser User

func getCurrentTime() string {
	currentTime := time.Now()

	currentTimeConverted := fmt.Sprintf("%d-%d-%d %d:%d:%d", currentTime.Day(), currentTime.Month(), currentTime.Year(), currentTime.Hour(), currentTime.Minute(), currentTime.Second())
	return currentTimeConverted
}

func saveLog(log Log) error {

	var f *os.File

	if _, err := os.Stat("log.txt"); errors.Is(err, os.ErrNotExist) {
		var err error

		f, err = os.Create("log.txt")

		if err != nil {
			return err
		}

		if err = f.Close(); err != nil {
			return err
		}

	} else if err != nil {
		return err
	}

	var err error

	if f, err = os.OpenFile("log.txt", os.O_APPEND|os.O_WRONLY, 0644); err != nil {
		return err
	}

	var jsonData []byte

	jsonData, err = json.Marshal(log)
	if err != nil {
		return err
	}

	_, err = fmt.Fprintln(f, string(jsonData))

	if err != nil {
		f.Close()
		return err
	}

	if err = f.Close(); err != nil {
		return err
	}

	return nil
}

func getLog() string {
	content, err := os.ReadFile("log.txt")
	if err != nil {
		fmt.Println("Hata: ", err)
		return ""
	}
	return string(content)
}

func getLastCustomerID() int {
	if len(usersList) == 0 {
		return 0
	}
	return usersList[len(usersList)-1].Id
}

func addCustomer() error {
	var username string
	var password string
	var err error

	fmt.Println("\n---------------------------------------")
	fmt.Println("Yeni Kullanıcının Kullancını Adını Giriniz")

	if _, err = fmt.Scan(&username); err != nil {
		return err
	}

	fmt.Println("Yeni Kullanıcının Şifresini Giriniz")

	if _, err = fmt.Scan(&password); err != nil {
		return err
	}

	newUser := User{
		Id:       getLastCustomerID() + 1,
		Username: username,
		Password: password,
	}

	usersList = append(usersList, newUser)

	var jsonData []byte

	if jsonData, err = json.Marshal(usersList); err != nil {
		return err
	}

	var f *os.File

	if f, err = os.OpenFile("users.txt", os.O_CREATE|os.O_WRONLY|os.O_TRUNC, 0644); err != nil {
		return err
	}

	if _, err = fmt.Fprintln(f, string(jsonData)); err != nil {
		return err
	}

	if err = f.Close(); err != nil {
		return err
	}

	return nil
}

func deleteCustomer() {
	var username string
	fmt.Println("Silmek istediğiniz kullanıcının kullanıcı adını giriniz:")
	fmt.Scan(&username)

	index := -1
	for i, user := range usersList {
		if user.Username == username {
			index = i
			break
		}
	}

	if index == -1 {
		fmt.Println("Kullanıcı bulunamadı.")
		return
	}

	usersList = append(usersList[:index], usersList[index+1:]...)

	jsonData, err := json.Marshal(usersList)
	if err != nil {
		fmt.Println("Hata: ", err)
		return
	}

	if err = os.WriteFile("users.txt", jsonData, 0644); err != nil {
		fmt.Println("Hata: ", err)
	}

	fmt.Println("Kullanıcı başarıyla silindi.")
}

func adminLogin() {
	for {

		var username string
		var password string

		fmt.Println("Kullanıcı Adınızı Giriniz: ")

		if _, err := fmt.Scan(&username); err != nil {
			fmt.Println(err)
			return
		}

		fmt.Println("Şifrenizi Giriniz: ")

		if _, err := fmt.Scan(&password); err != nil {
			fmt.Println(err)
			return
		}

		log := Log{}

		log.Username = username
		log.LogDate = getCurrentTime()

		if username != "admin" || password != "admin" {

			log.LoginStatus = false
			saveLog(log)
			fmt.Println("Giriş Başarısız")

		} else {
			log.LoginStatus = true
			saveLog(log)

			fmt.Println("Giriş Başarılı")
			break
		}
	}
Loop:
	for {

		fmt.Println("Ne Yapmak İstiyorsunuz ?\n1 - Müşteri Ekleme\n2 - Müşteri Silme\n3 - Log Listeleme\n\n---------------------------------------------\n0-Çıkış Yap")

		var optionInt int
		var err error

		if _, err = fmt.Scan(&optionInt); err != nil {
			fmt.Println(err)
			break
		}

		switch optionInt {
		case 0:
			fmt.Println("Çıkış Yapıldı")
			break Loop
		case 1:
			if err = addCustomer(); err != nil {
				fmt.Println("Hata: ", err)
			} else {
				fmt.Println("----------------------------------")
				fmt.Println("| Kullanıcı Başarıyla Oluşturuldu |")
				fmt.Println("----------------------------------")
			}
		case 2:
			deleteCustomer()
		case 3:
			fmt.Println(getLog())
		}
	}
}

func showProfile() error {
	fmt.Println("İsim: ", activeUser.Name, "\nSoyisim: ", activeUser.Surname, "\nKullanıcı Adı: ", activeUser.Username)
	return nil
}

func updateProfile() error {

	var name string
	var surname string

	fmt.Println("Yeni isim giriniz:")

	if _, err := fmt.Scan(&name); err != nil {
		return err
	}

	fmt.Println("Yeni soyisim giriniz:")

	if _, err := fmt.Scan(&surname); err != nil {
		return err
	}

	activeUser.Name = name
	activeUser.Surname = surname

	for i := range usersList {
		if usersList[i].Id == activeUser.Id {
			usersList[i].Name = name
			usersList[i].Surname = surname
			break
		}
	}

	jsonData, err := json.Marshal(usersList)
	if err != nil {
		return err
	}

	if err := os.WriteFile("users.txt", jsonData, 0644); err != nil {
		return err
	}

	fmt.Println("Şifre başarıyla değiştirildi.")
	return nil
}

func changePassword() error {
	var oldPassword, newPassword string
	fmt.Println("Mevcut şifrenizi giriniz:")
	fmt.Scan(&oldPassword)

	if oldPassword != activeUser.Password {
		return errors.New("eski şifre yanlış")
	}

	fmt.Println("Yeni şifrenizi giriniz:")
	fmt.Scan(&newPassword)

	activeUser.Password = newPassword
	for i := range usersList {
		if usersList[i].Id == activeUser.Id {
			usersList[i].Password = newPassword
			break
		}
	}

	jsonData, err := json.Marshal(usersList)
	if err != nil {
		return err
	}

	if err := os.WriteFile("users.txt", jsonData, 0644); err != nil {
		return err
	}

	fmt.Println("Şifre başarıyla değiştirildi.")
	return nil
}

func getUser(username string) *User {
	for i := range usersList {
		if usersList[i].Username == username {
			return &usersList[i]
		}
	}
	return nil
}

func customerLogin() {

	for {

		var username string
		var password string

		fmt.Println("Kullanıcı Adınızı Giriniz: ")

		if _, err := fmt.Scan(&username); err != nil {
			fmt.Println(err)
			return
		}

		fmt.Println("Şifrenizi Giriniz: ")

		if _, err := fmt.Scan(&password); err != nil {
			fmt.Println(err)
			return
		}

		log := Log{}

		log.Username = username
		log.LogDate = getCurrentTime()

		user := getUser(username)

		if user == nil {
			fmt.Println("Kullanıcı Bulunamadı")
			log.LoginStatus = false
			saveLog(log)
		} else {
			if password != user.Password {
				log.LoginStatus = false
				saveLog(log)
				fmt.Println("Giriş Başarısız")
			} else {
				activeUser = *user
				log.LoginStatus = true
				saveLog(log)
				fmt.Println("Giriş Başarılı")
				break
			}
		}
	}
Loop:
	for {

		fmt.Println("Ne Yapmak İstiyorsunuz ?\n1 - Profil Görüntüle\n2 - Profil Güncelle\n3 - Şifre Değiştir\n\n---------------------------------------------\n0-Çıkış Yap")

		var optionInt int
		var err error

		if _, err = fmt.Scan(&optionInt); err != nil {
			fmt.Println(err)
			break
		}

		switch optionInt {
		case 0:
			fmt.Println("Çıkış Yapıldı")
			break Loop
		case 1:
			if err = showProfile(); err != nil {
				fmt.Println("Hata: ", err)
			}
		case 2:
			if err = updateProfile(); err != nil {
				fmt.Println("Hata: ", err)
			} else {
				fmt.Println("Profiliniz başarıyla güncellendi")
			}

		case 3:
			if err = changePassword(); err != nil {
				fmt.Println("Hata: ", err)
			} else {
				fmt.Println("Şifreniz başarıyla değiştirildi")
			}
		}
	}
}

func loadUsers() error {
	file, err := os.Open("users.txt")
	if err != nil {
		if errors.Is(err, os.ErrNotExist) {
			fmt.Println("users.txt bulunamadı, yeni bir dosya oluşturulacak.")
			usersList = []User{}
			return nil
		}
		return err
	}
	defer file.Close()

	fileInfo, err := file.Stat()
	if err != nil {
		return err
	}

	if fileInfo.Size() == 0 {
		fmt.Println("users.txt boş, kullanıcı listesi başlatıldı.")
		usersList = []User{}
		return nil
	}

	jsonData, err := io.ReadAll(file)
	if err != nil {
		return err
	}

	err = json.Unmarshal(jsonData, &usersList)
	if err != nil {
		fmt.Println("users.txt dosyasındaki veri JSON formatında değil, kullanıcı listesi boş olarak başlatıldı.")
		usersList = []User{}
		return nil
	}

	return nil
}

func main() {
	if err := loadUsers(); err != nil {
		fmt.Println("Hata: ", err)
		return
	}

	var option int
	for {
		fmt.Println("1 - Admin Girişi\n2 - Kullanıcı Girişi\n0 - Çıkış")
		fmt.Scan(&option)

		switch option {
		case 1:
			adminLogin()
		case 2:
			customerLogin()
		case 0:
			fmt.Println("Programdan çıkılıyor...")
			return
		default:
			fmt.Println("Geçersiz seçenek.")
		}
	}
}
