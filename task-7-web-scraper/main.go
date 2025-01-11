package main

import (
	"fmt"
	"os"
	"strings"

	"github.com/gocolly/colly"
)

func writeToTextFile(title string, date string, desc string) error {
	var err error

	_, err = os.Stat("output.txt")

	if err != nil {
		_, err = os.Create("output.txt")
		if err != nil {
			return err
		}
	}

	var data []byte

	data, err = os.ReadFile("output.txt")

	if err != nil {
		return err
	}

	fmt.Println(string(data))

	msg := []byte(title + "\n" + date + "\n\n" + desc + "\n\n\n\n")

	data = append(data, msg...)

	err = os.WriteFile("output.txt", data, 0777)

	if err != nil {
		return err
	}

	return nil
}

func scrapTestSite() {
	c := colly.NewCollector()

	c.OnHTML(".product-wrapper", func(h *colly.HTMLElement) {
		title := h.ChildText(".title")
		price := h.ChildText(".price")
		desc := h.ChildText(".description")

		title = strings.TrimSpace(title)
		price = strings.TrimSpace(price)

		if err := writeToTextFile(title, price, desc); err != nil {
			fmt.Println(err)
			return
		}
	})

	err := c.Visit("https://webscraper.io/test-sites/e-commerce/allinone")

	if err != nil {
		fmt.Println(err)
	}

	fmt.Println("Çekildi")
}

func scrapWebTekno() {
	c := colly.NewCollector()

	c.OnHTML(".content-timeline__item", func(h *colly.HTMLElement) {
		title := h.ChildText(".content-timeline--underline")
		date := h.ChildAttr("time", "datetime")
		desc := ""

		title = strings.TrimSpace(title)
		date = strings.TrimSpace(date)

		if err := writeToTextFile(title, date, desc); err != nil {
			fmt.Println(err)
			return
		}
	})

	err := c.Visit("https://www.webtekno.com")

	if err != nil {
		fmt.Println(err)
	}

	fmt.Println("Çekildi")
}

func scrapWebSite(url string) {

	c := colly.NewCollector()

	c.OnHTML(".body-post", func(h *colly.HTMLElement) {
		title := h.ChildText(".home-title")
		date := h.ChildText(".h-datetime")
		desc := h.ChildText(".home-desc")

		title = strings.TrimSpace(title)
		date = strings.TrimSpace(date)
		desc = strings.TrimSpace(desc)

		if err := writeToTextFile(title, date, desc); err != nil {
			fmt.Println(err)
			return
		}
	})

	err := c.Visit(url)

	if err != nil {
		fmt.Println(err)
	}

	fmt.Println("Çekildi")
}

func main() {
	var option int

	for {
		fmt.Println("Araştırmak İstediğiniz Siteyi Seçin.\n1- The Hacker News\n2- Webtekno\n3- Web Scraper Test Sitesi\n4- Çıkış Yap")
		fmt.Scan(&option)

		switch option {
		case 1:
			scrapWebSite("https://thehackernews.com/")
		case 2:
			scrapWebTekno()
		case 3:
			scrapTestSite()
		case 4:
			fmt.Println("Program Kapatılıyor")
			return
		default:
			fmt.Println("Geçersiz Girdi")
		}
	}
}
