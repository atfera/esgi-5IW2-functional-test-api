Feature: Offers
  In order to test offers CRUD
  As an recruiter
  I need to be able to create get list update and delete offers

  Background:
    Given the following fixtures files are loaded:
      | user          |
      | status        |
      | offers        |
      | applications  |

  Scenario: Get offer with applicant user
    Given I authenticate with user "{{ user_1.email }}" and password "applicant"
    Given I request "GET /offers/{{ offer_1.id }}"
    And the response status code should be 404

  Scenario: Post offer with recruiter user
    Given I authenticate with user "{{ user_6.email }}" and password "recruiter"
    Given I have the payload
    """
    {
        "name": "Name test",
        "descriptionCompany": "Company test",
        "description": "Desc test",
        "typeContract": "CDI",
        "workplace": "Workplace test"
    }
    """
    Given I request "POST /offers"
    And the response status code should be 201
    And the "name" property should equal "Name test"
    Then print last response

