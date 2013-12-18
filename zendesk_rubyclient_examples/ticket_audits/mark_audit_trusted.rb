require 'zendesk_api'
require 'json'
require_relative "../lib/client"

ticket = client.ticket.find(:id => 187).audits(:id => 17889077403)

puts ticket.trust.fetch