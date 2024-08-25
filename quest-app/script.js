class QuestionPackage{
    constructor(id, title, question, optionA, optionB, optionC, optionD, correctOption, difficulty){
        this.id = id;
        this.title = title;
        this.question = question;
        this.optionA = optionA;
        this.optionB = optionB;
        this.optionC = optionC;
        this.optionD = optionD;
        this.correctOption = correctOption;
        this.difficulty = difficulty;
    }

    get Id(){
        return this.id;
    }
    get Title(){
        return this.title;
    }
    get Difficulty(){
        return this.difficulty;
    }

    get Question(){
        return this.question;
    }
    get Options(){
        return [this.optionA, this.optionB, this.optionC, this.optionD];
    }
    get CorrectOption(){
        return this.correctOption;
    }
}

questions = JSON.parse(localStorage.getItem('questions')) || [];

function addQuestion(event){
    event.preventDefault();
    

      const form = event.target;
      const formData = new FormData(form);
      const formObject = {};
    
      formData.forEach((value, key) => {
        formObject[key] = value;
      });
      
    
      const questionPackage = new QuestionPackage(
        questions.length,
        formObject.T,
        formObject.Q,
        formObject.A,
        formObject.B,
        formObject.C,
        formObject.D,
        formObject.correctAnswer,
        formObject.difficulty
      );
    
      questions.push(questionPackage);

      localStorage.setItem('questions', JSON.stringify(questions));

      alert("Soru Eklendi");
      window.location.href = '../admin-panel.html';
    
}

