require 'zendesk_api'
require 'json'
require_relative "../lib/client"

ticket = client.tickets.find(:id => 207).incidents

puts ticket.fetch
