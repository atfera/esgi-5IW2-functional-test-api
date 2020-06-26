Feature: Test Offers features
  Background:
    Given the following fixtures files are loaded:
      | user          |
      | status        |
      | offers        |
      | applications  |



    Scenario: Create a new Offer as applicant and check if inserted
        Given I authenticate with user "{{user_1.email}}" and password "applicant"
        And the response status code should be 200
        And scope into the "token" property
        And the "token" property should be a string
        And reset scope
        Given I have the payload
        """
        {
        "name": "Contrat random",
        "descriptionCompany": "All things random",
        "description": "RandomCorp",
        "typeContract": "CDD",
        "workplace": "Bourg-Palette"
        }
        """
        Given I request "POST /offers"
        And the response status code should be 403

    Scenario: Create a new Offer as recruiter and check if inserted
        Given I authenticate with user "{{user_6.email}}" and password "recruiter"
        And the response status code should be 200
        And scope into the "token" property
        And the "token" property should be a string
        And reset scope
        Given I have the payload
        """
        {
        "name": "Contrat random",
        "descriptionCompany": "All things random",
        "description": "RandomCorp",
        "typeContract": "CDD",
        "workplace": "Bourg-Palette"
        }
        """
        Given I request "POST /offers"
        And the response status code should be 201
        Given I request "GET /offers"
        And the response status code should be 403
