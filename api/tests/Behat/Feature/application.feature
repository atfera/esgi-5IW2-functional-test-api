Feature: Test Application features
  Background:
    Given the following fixtures files are loaded:
      | user          |
      | status        |
      | offers        |
      | applications  |

    Scenario: Create a new Application as an applicant and check if inserted
        Given I authenticate with user "{{user_1.email}}" and password "applicant"
        And the response status code should be 200
        And scope into the "token" property
        And the "token" property should be a string
        And reset scope
        Given I have the payload
        """
        {
        "name": "Dias",
        "firstName": "Yann"
        "gender": "male"
        "email": "test@test.fr"
        "age": "29"
        "address" : "5 allee du random 75000 Paris"
        "motivationField": "blah blah blah"
        "salaryClaim": 45000
        }
        """
        Given I request "POST /applications"
        And the response status code should be 400

    Scenario: Create a new Application as applicant and check if inserted
        Given I authenticate with user "{{user_1.email}}" and password "applicant"
        And the response status code should be 200
        And scope into the "token" property
        And the "token" property should be a string
        And reset scope
        Given I have the payload
        """
        {
          "name": "Dias",
          "firstName": "Yann",
          "gender": "male",
          "email": "test@test.fr",
          "age": "29",
          "address" : "5 allee du random 75000 Paris",
          "motivationField": "blah blah blah",
          "salaryClaim": 45000
        }
        """
        Given I request "POST /applications"
        And the response status code should be 201
        Given I request "GET /applications"
        And the response status code should be 200
