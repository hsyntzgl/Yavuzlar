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

    AddQuestion(formObject){

    }
}