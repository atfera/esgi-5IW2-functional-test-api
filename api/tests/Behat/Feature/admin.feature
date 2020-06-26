Feature: Test admin features
  In order to test admin features
  I need to be able to get all users and offers

  Background:
    Given the following fixtures files are loaded:
      | user        |
      | offers      |

  Scenario: Get all users with applicant account
    Given I authenticate with user "{{ user_1.email }}" and password "applicant"
    Given I request "GET /users"
    And the response status code should be 400

  Scenario: Get all offers with recruiter account
    Given I authenticate with user "{{ user_6.email }}" and password "recruiter"
    Given I request "GET /offers"
    And the response status code should be 400

  Scenario: Get all users with admin account
    Given I authenticate with user "{{ user_11.email }}" and password "admin"
    Given I request "GET /users"
    And the response status code should be 200
    And the "hydra:totalItems" property should be an integer equalling "11"
    Then print last response

  Scenario: Get all offers with admin account
    Given I authenticate with user "{{ user_11.email }}" and password "admin"
    Given I request "GET /offers"
    And the response status code should be 200
    And the "hydra:totalItems" property should be an integer equalling "10"
    Then print last response

