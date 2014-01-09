require 'zendesk_api'
require 'json'
require_relative "../lib/client"

ticket = client.ticket.find(:id => 187).audits

puts ticket.fetch