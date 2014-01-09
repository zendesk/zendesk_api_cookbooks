require 'zendesk_api'
require 'json'
require_relative "../lib/client"

ticket = client.tickets.find(:id => 187).comments

puts ticket.fetch