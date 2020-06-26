Feature: Test Offers features
  Background:
    Given the following fixtures files are loaded:
      | user     |
      | applications     |
      | offers     |

    Scenario: Create a new Offer as applicant and check if inserted
        Given I authenticate with user "{{user_1.email}}" and password "{{user_1.password}}"
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
        "workplace": "Bourg-Palette",
        "recruiter": "{user_6.id}"
        }
        """
        Given I request "POST /offers"
        And the response status code should be 403
        Given I request "GET /offers"
        And the response status code should be 200
        And the "hydra:totalItems" property should be an integer equalling "10"

    Scenario: Create a new Offer as recruiter and check if inserted
        Given I authenticate with user "{{user_6.email}}" and password "{{user_6.password}}"
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
        "workplace": "Bourg-Palette",
        "recruiter": "{user_6.id}"
        }
        """
        Given I request "POST /offers"
        And the response status code should be 201
        Given I request "GET /offers"
        And the response status code should be 200
        And the "hydra:totalItems" property should be an integer equalling "11"