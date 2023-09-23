Feature: Show meeting
  In order to participate in a meeting
  I want to see single meeting with the meeting details and whether the meeting is available

  Background:
    Given the app has a meeting with fewer than "5" participants and with name "Open to registration meeting", but didn't start yet
    And the app has a meeting with "5" participants and with name "Full meeting", but didn't start yet
    And the app has a meeting with name "In session", started but didn't finish
    And the app has a meeting with name "Done", finished

  Scenario:
    When the app sends a request for meeting with name "Open to registration meeting"
    And the app sends a request for the meeting status
    Then the json response should be received with data for the meeting
    And the json response should be received with status for the meeting
    And the meeting should be available

  Scenario:
    When the app sends a request for meeting with name "Full meeting"
    And the app sends a request for the meeting status
    Then the json response should be received with data for the meeting
    And the json response should be received with status for the meeting
    And the meeting should not be available

  Scenario:
    When the app sends a request for meeting with name "In session"
    And the app sends a request for the meeting status
    Then the json response should be received with data for the meeting
    And the json response should be received with status for the meeting
    And the meeting should not be available

  Scenario:
    When the app sends a request for meeting with name "Done"
    And the app sends a request for the meeting status
    Then the json response should be received with data for the meeting
    And the json response should be received with status for the meeting
    And the meeting should not be available
