Feature: Test Application features
  Background:
    Given the following fixtures files are loaded:
      | user     |
      | applications     |
      | offers     |

    Scenario: Create a new Application as recruiter and check if inserted
        Given I authenticate with user "{{user_6.email}}" and password "{{user_6.password}}"
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
        "age": 29
        "address" : "5 allee du random 75000 Paris"
        "motivationField": "blah blah blah"
        "salaryClaim": 45000
        }
        """
        Given I request "POST /applications"
        And the response status code should be 403
        Given I request "GET /applications"
        And the response status code should be 200
        And the "hydra:totalItems" property should be an integer equalling "100"

    Scenario: Create a new Application as applicant and check if inserted
        Given I authenticate with user "{{user_1.email}}" and password "{{user_1.password}}"
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
        "age": 29
        "address" : "5 allee du random 75000 Paris"
        "motivationField": "blah blah blah"
        "salaryClaim": 45000
        }
        """
        Given I request "POST /applications"
        And the response status code should be 201
        Given I request "GET /applications"
        And the response status code should be 200
        And the "hydra:totalItems" property should be an integer equalling "101"