Feature: Create and validate account
  In order to test creation of an account
  I need to be able to create an account

  Background:
    Given the following fixtures files are loaded:
      | user       |

  Scenario: Post user with recruiter role
    Given I am not authenticated
    Given I have the payload
    """
    {
        "email": "recruiter@test.com",
        "roles": [
          "ROLE_USER",
          "ROLE_RECRUITER"
        ],
        "password": "recruiter"
    }
    """
    Given I request "POST /users"
    And the response status code should be 201
    And the "email" property should equal "recruiter@test.com"
    Given I validate the account
    And the response status code should be 200
    And the "isActive" property should be a boolean equalling "true"
    Given I request "GET /users/{{ user_1.id }}"
    And the response status code should be 200
    Then print last response
