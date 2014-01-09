require 'zendesk_api'
require 'json'
require_relative "../lib/client"

#Find the ticket by ID
ticket = client.tickets.find(:id => 151)

#Update fields
ticket.update(:status => "open", :tags => ["update1", "update2", "update3"])

#Save the ticket once you update the fields
ticket.save
puts ticket.inspect
