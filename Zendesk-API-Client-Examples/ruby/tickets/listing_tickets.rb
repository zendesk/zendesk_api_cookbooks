require 'zendesk_api'
require 'json'

client = ZendeskAPI::Client.new do |config|
  config.url = ENV['ZD_URL']
  config.username = ENV['ZD_USER']
  config.password = ENV['ZD_PASS']
  config.retry = true
  require 'logger'
  config.logger = Logger.new(STDOUT)
end

#List Tickets
client.tickets.all do |ticket|
  puts ticket.inspect
end

#List Tickets By Organization
org = client.organizations.first # find the organization
org.tickets.all do |ticket|
  puts ticket.inspect
end

#List User's Requested Tickets
user = client.users.first # find the user
user.requested_tickets.all do |ticket|
  puts ticket.inspect
end

#List Tickets User is CC'd
user = client.users.first
user.ccd_tickets.all do |ticket|
  puts ticket.inspect
end

#List Recent Tickets
client.tickets.recent.each do |ticket|
  puts ticket.inspect
end

